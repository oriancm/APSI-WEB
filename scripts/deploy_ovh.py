"""Deploy application files only. Production data always remains authoritative."""
import argparse
import getpass
import io
import json
import os
from pathlib import Path, PurePosixPath
import shlex
import sys
import tarfile

import paramiko

HOST = "ssh.cluster126.hosting.ovh.net"
USER = "apsibtl"
ROOT = "/home/apsibtl/www"
BRANCH = "codex/secure-contabo-deploy"


def protected(path):
    parts = PurePosixPath(path).parts
    return (
        not parts or path.startswith("/") or ".." in parts
        or any(p == ".git" or p == "secrets" or p.startswith(".env") for p in parts)
        or parts[0] in {"pic", "bdd", "output", "backups", "node_modules"}
        or path.endswith((".sql", ".sql.gz"))
    )


def run(client, command):
    _, stdout, stderr = client.exec_command(command)
    # Read both channels concurrently to avoid blocking on a full stderr buffer.
    import concurrent.futures
    with concurrent.futures.ThreadPoolExecutor(max_workers=2) as pool:
        out = pool.submit(stdout.read)
        err = pool.submit(stderr.read)
        output, errors = out.result(), err.result()
    if stdout.channel.recv_exit_status() != 0:
        raise RuntimeError("Remote command failed: " + errors.decode(errors="replace")[-1500:])
    return output


def snapshot(client, guard, root, backup=None):
    cmd = "php " + shlex.quote(guard) + " " + shlex.quote(root)
    if backup:
        cmd += " " + shlex.quote(backup)
    return json.loads(run(client, cmd))


def archive_files(blob):
    result = []
    with tarfile.open(fileobj=io.BytesIO(blob)) as archive:
        for member in archive.getmembers():
            if member.isdir():
                continue
            if protected(member.name):
                continue
            if any(c in member.name for c in "\n\r\0") or member.name.startswith("-"):
                raise RuntimeError("Invalid archive path")
            if not member.isfile():
                raise RuntimeError("Deployment refuses links: " + member.name)
            result.append(member.name)
    return result


def deploy(client, branch=BRANCH, check_only=False):
    q = shlex.quote
    home = str(PurePosixPath(ROOT).parent)
    lock = home + "/.apsi-deploy-lock"
    run(client, "mkdir " + q(lock))
    stage = None
    try:
        stage = run(client, "umask 077; mktemp -d " + q(home + "/.apsi-deploy-XXXXXXXX")).decode().strip()
        if not stage.startswith(home + "/.apsi-deploy-"):
            raise RuntimeError("Unexpected staging directory")
        sftp = client.open_sftp()
        try:
            guard = stage + "/guard.php"
            sftp.put(str(Path(__file__).with_name("deployment_guard.php")), guard)
            sftp.chmod(guard, 0o600)
        finally:
            sftp.close()
        before = snapshot(client, guard, ROOT, stage + "/database-before.serialized")
        print("Production database verified; private backup saved in " + stage)
        if check_only:
            print("Read-only check passed. No application files changed.")
            return
        if branch.startswith("-"):
            raise ValueError("Invalid branch")
        run(client, "cd " + q(ROOT) + " && git fetch origin " + q(branch))
        commit = run(client, "cd " + q(ROOT) + " && git rev-parse FETCH_HEAD").decode().strip()
        if len(commit) != 40 or any(c not in "0123456789abcdef" for c in commit):
            raise RuntimeError("Invalid commit")
        blob = run(client, "cd " + q(ROOT) + " && git archive " + commit)
        files = archive_files(blob)
        if not files or "admin/db.php" not in files:
            raise RuntimeError("Incomplete application archive")
        # Build a code-only archive; database dumps, server settings and photos never enter it.
        payload = io.BytesIO()
        with tarfile.open(fileobj=io.BytesIO(blob)) as source, tarfile.open(fileobj=payload, mode="w") as target:
            for name in files:
                member = source.getmember(name)
                target.addfile(member, source.extractfile(member))
        sftp = client.open_sftp()
        try:
            with sftp.open(stage + "/code.tar", "wb") as stream:
                stream.write(payload.getvalue())
        finally:
            sftp.close()
        candidate = stage + "/candidate"
        run(client, "mkdir " + q(candidate) + " && tar -xf " + q(stage + "/code.tar") + " -C " + q(candidate))
        # Use only production connection settings for candidate validation.
        run(client, "for f in .env admin/.env; do if [ -f " + q(ROOT) + '/"$f" ]; then cp -p ' + q(ROOT) + '/"$f" ' + q(candidate) + '/"$f"; fi; done')
        run(client, "ln -s " + q(ROOT + "/pic") + " " + q(candidate + "/pic"))
        lint = 'for f do php -l "$f" || exit 1; done'
        run(client, "find " + q(candidate) + " -name '*.php' -type f -exec sh -c " + q(lint) + " sh {} + > " + q(stage + "/php-lint.log") + " 2>&1")
        staged = snapshot(client, guard, candidate)
        if staged != before:
            raise RuntimeError("Candidate does not read the same production database; deployment blocked")
        # Keep a private code rollback archive; never back up inside the public directory.
        sftp = client.open_sftp()
        existing = []
        new = []
        try:
            import stat
            for name in files:
                target = ROOT + "/" + name
                # Refuse symlink parents as well as symlink targets.
                for parent in PurePosixPath(name).parents:
                    if str(parent) == ".":
                        continue
                    try:
                        if stat.S_ISLNK(sftp.lstat(ROOT + "/" + str(parent)).st_mode):
                            raise RuntimeError("Deployment refuses symlink parent: " + name)
                    except FileNotFoundError:
                        pass
                try:
                    mode = sftp.lstat(target).st_mode
                    if not stat.S_ISREG(mode):
                        raise RuntimeError("Deployment refuses non-file target: " + name)
                    existing.append(name)
                except FileNotFoundError:
                    new.append(name)
            with sftp.open(stage + "/existing.txt", "w") as stream:
                stream.write("".join(name + "\n" for name in existing))
            with sftp.open(stage + "/new.txt", "w") as stream:
                stream.write("".join(name + "\n" for name in new))
        finally:
            sftp.close()
        run(client, "tar -cf " + q(stage + "/code-before.tar") + " -C " + q(ROOT) + " --verbatim-files-from -T " + q(stage + "/existing.txt"))
        try:
            # No git reset, SQL import, environment copy or removal of uploaded files.
            run(client, "tar -xf " + q(stage + "/code.tar") + " -C " + q(ROOT))
            after = snapshot(client, guard, ROOT)
            if after != before:
                raise RuntimeError("Production data changed during deployment; manual review required")
        except Exception:
            run(client, "tar -xf " + q(stage + "/code-before.tar") + " -C " + q(ROOT))
            # Remove only the newly introduced application files listed in this deployment.
            run(client, "cd " + q(ROOT) + '; while IFS= read -r f; do rm -f -- "$f"; done < ' + q(stage + "/new.txt"))
            raise
        print("Deployed " + commit[:12] + ". Database, admin credentials, settings and photos unchanged.")
    finally:
        # Retain private backups and logs, including on failure. Never restore a database automatically.
        run(client, "rmdir " + q(lock))


def main():
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("--check-only", action="store_true", help="Verify and back up production without deploying")
    parser.add_argument("--branch", default=BRANCH)
    args = parser.parse_args()
    client = paramiko.SSHClient()
    client.load_system_host_keys()
    client.set_missing_host_key_policy(paramiko.RejectPolicy())
    try:
        client.connect(HOST, username=USER,
                       password=os.environ.get("APSI_SSH_PASSWORD") or getpass.getpass("OVH SSH password: "),
                       timeout=20)
        deploy(client, args.branch, args.check_only)
    except Exception as exc:
        print("Deployment stopped: " + str(exc), file=sys.stderr)
        return 1
    finally:
        client.close()
    return 0


if __name__ == "__main__":
    sys.exit(main())
