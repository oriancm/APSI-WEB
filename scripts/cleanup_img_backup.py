import os
import re

base_dir = r"C:\Users\orian\code\APSI-WEB"
img_backup_dir = os.path.join(base_dir, "img_backup")

print("==================================================")
print("  APSI img_backup Cleanup Manager")
print("==================================================\n")

# 1. Gather all files in the codebase (excluding backup, old, node_modules etc.)
code_files = []
for root, dirs, files in os.walk(base_dir):
    if any(p in root for p in ["img_backup", "pic_backup", "old img", ".git", ".idea", "node_modules", "scripts"]):
        continue
    for f in files:
        if f.endswith(('.php', '.css', '.js', '.html', '.htaccess', '.json')):
            code_files.append(os.path.join(root, f))

# Read all code content into a lowercase block of text
code_content = ""
for cf in code_files:
    try:
        with open(cf, "r", encoding="utf-8", errors="ignore") as file:
            code_content += "\n" + file.read().lower()
    except Exception as e:
        print(f"[-] Error reading {cf}: {e}")

# 2. Gather all files in img_backup
backup_files = [f for f in os.listdir(img_backup_dir) if os.path.isfile(os.path.join(img_backup_dir, f))]

keep_files = []
delete_files = []

for f in backup_files:
    base_name, ext = os.path.splitext(f.lower())
    
    # We check if the basename is mentioned in the codebase (e.g., logo-nav, background1)
    # To be extremely precise, we look for matches of the basename as a word or inside strings (like "logo-nav.avif")
    # Using a simple check `base_name in code_content`
    if base_name in code_content:
        keep_files.append(f)
    else:
        delete_files.append(f)

print(f"Total backup files found: {len(backup_files)}")
print(f"Files to KEEP: {len(keep_files)}")
for kf in sorted(keep_files):
    print(f"  [KEEP] {kf}")

print(f"\\nFiles to DELETE: {len(delete_files)}")
for df in sorted(delete_files):
    print(f"  [DELETE] {df}")

# 3. Perform Deletion
print("\nPerforming deletion...")
deleted_count = 0
for df in delete_files:
    path = os.path.join(img_backup_dir, df)
    try:
        os.remove(path)
        print(f"  [+] Deleted: {df}")
        deleted_count += 1
    except Exception as e:
        print(f"  [-] Failed to delete {df}: {e}")

print(f"\n[+] Cleanup complete! Successfully deleted {deleted_count} unused original backup files from img_backup.")
