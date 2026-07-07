import paramiko
import sys

hostname = "ssh.cluster126.hosting.ovh.net"
username = "apsibtl"
password = "34KAseb2sjK3Fn9"
branch = "codex/secure-contabo-deploy"

print(f"Connecting to {hostname} via SSH as {username}...")
client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())

try:
    client.connect(hostname, username=username, password=password, timeout=15)
    print("Connected successfully!")
    
    commands = [
        f"cd /home/apsibtl/www && git fetch origin",
        f"cd /home/apsibtl/www && git reset --hard origin/{branch}",
        "cd /home/apsibtl/www && git status"
    ]
    
    for cmd in commands:
        print(f"\n--- Executing: {cmd} ---")
        stdin, stdout, stderr = client.exec_command(cmd)
        
        out = stdout.read().decode('utf-8', errors='ignore')
        err = stderr.read().decode('utf-8', errors='ignore')
        
        if out:
            print("STDOUT:")
            print(out)
        if err:
            print("STDERR:")
            print(err)
            
except Exception as e:
    print(f"Error occurred during deployment: {e}", file=sys.stderr)
    sys.exit(1)
finally:
    client.close()
    print("\nConnection closed. Deployment finished.")
