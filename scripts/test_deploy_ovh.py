import io
import tarfile
import unittest
from unittest.mock import MagicMock, patch

import deploy_ovh as deploy


def archive(entries):
    buffer = io.BytesIO()
    with tarfile.open(fileobj=buffer, mode='w') as output:
        for name in entries:
            entry = tarfile.TarInfo(name)
            entry.size = 5
            output.addfile(entry, io.BytesIO(b'<?php'))
    return buffer.getvalue()


class DeploymentTests(unittest.TestCase):
    def test_remote_failure_is_not_reported_as_success(self):
        client = MagicMock()
        stdout, stderr = MagicMock(), MagicMock()
        stdout.read.return_value = b''
        stderr.read.return_value = b'precondition failed'
        stdout.channel.recv_exit_status.return_value = 1
        client.exec_command.return_value = (None, stdout, stderr)
        with self.assertRaisesRegex(RuntimeError, 'precondition failed'):
            deploy.run(client, 'failing-command')

    def test_archive_links_are_rejected(self):
        buffer = io.BytesIO()
        with tarfile.open(fileobj=buffer, mode='w') as output:
            entry = tarfile.TarInfo('admin/config.php')
            entry.type = tarfile.SYMTYPE
            entry.linkname = '../.env'
            output.addfile(entry)
        with self.assertRaises(RuntimeError):
            deploy.archive_files(buffer.getvalue())

    def test_production_files_are_excluded(self):
        files = deploy.archive_files(archive([
            'admin/db.php', 'index.php', '.env', 'admin/.env', 'secrets/password',
            'pic/project.webp', 'pic/cards/project.webp', 'bdd/apsi.sql',
            'backup.sql', 'backups/database', 'output/audit.py', '../escape.php',
        ]))
        self.assertEqual(files, ['admin/db.php', 'index.php'])

    def setup_deployment(self, snapshots):
        client = MagicMock()
        client.open_sftp.return_value.lstat.side_effect = FileNotFoundError
        commands = []

        def run(_, cmd):
            commands.append(cmd)
            if 'mktemp' in cmd:
                return b'/home/apsibtl/.apsi-deploy-test\n'
            if 'rev-parse' in cmd:
                return b'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa\n'
            if 'git archive' in cmd:
                return archive(['admin/db.php', 'index.php', '.env', 'pic/test.webp', 'bdd/test.sql'])
            return b''

        return client, commands, patch.object(deploy, 'run', side_effect=run), patch.object(deploy, 'snapshot', side_effect=snapshots)

    def test_candidate_database_mismatch_never_updates_live_code(self):
        client, commands, remote, snapshots = self.setup_deployment([{'hash': 'online'}, {'hash': 'other'}])
        with remote, snapshots, self.assertRaises(RuntimeError):
            deploy.deploy(client)
        self.assertFalse(any('tar -xf' in c and c.endswith('-C ' + deploy.ROOT) for c in commands))
        self.assertTrue(commands[-1].startswith('rmdir '))

    def test_postcheck_failure_restores_code_without_restoring_database(self):
        client, commands, remote, snapshots = self.setup_deployment([{'hash': 'online'}, {'hash': 'online'}, {'hash': 'changed'}])
        with remote, snapshots, self.assertRaises(RuntimeError):
            deploy.deploy(client)
        self.assertTrue(any('tar -xf' in c and '/code-before.tar' in c for c in commands))
        self.assertTrue(any('rm -f -- "$f"' in c for c in commands))
        self.assertFalse(any('mysql' in c or 'reset --hard' in c for c in commands))

    def test_check_only_never_fetches_or_updates_code(self):
        client, commands, remote, snapshots = self.setup_deployment([{'hash': 'online'}])
        with remote, snapshots:
            deploy.deploy(client, check_only=True)
        self.assertFalse(any('git ' in c or 'tar -xf' in c for c in commands))

    def test_failed_precheck_stops_before_fetch(self):
        client, commands, remote, snapshots = self.setup_deployment([RuntimeError('Database unavailable')])
        with remote, snapshots, self.assertRaises(RuntimeError):
            deploy.deploy(client)
        self.assertFalse(any('git ' in c for c in commands))

    def test_success_checks_live_data_again(self):
        state = {'hash': 'online'}
        client, commands, remote, snapshots = self.setup_deployment([state, state, state])
        with remote, snapshots as check:
            deploy.deploy(client)
        self.assertEqual(check.call_count, 3)
        self.assertTrue(any('tar -xf' in c and '/code.tar' in c and c.endswith('-C ' + deploy.ROOT) for c in commands))


if __name__ == '__main__':
    unittest.main()
