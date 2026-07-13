import os
import re
import shutil

base_dir = r"C:\Users\orian\code\APSI-WEB"
img_dir = os.path.join(base_dir, "img")
pic_dir = os.path.join(base_dir, "pic")
img_backup_dir = os.path.join(base_dir, "img_backup")
pic_backup_dir = os.path.join(base_dir, "pic_backup")
old_img_dir = os.path.join(base_dir, "old img")

# Create old img directory
os.makedirs(old_img_dir, exist_ok=True)
os.makedirs(os.path.join(old_img_dir, "img_backup"), exist_ok=True)
os.makedirs(os.path.join(old_img_dir, "pic_backup"), exist_ok=True)

# 1. Gather all files in compressed folders
img_files = {f.lower(): f for f in os.listdir(img_dir) if os.path.isfile(os.path.join(img_dir, f))}
pic_files = {f.lower(): f for f in os.listdir(pic_dir) if os.path.isfile(os.path.join(pic_dir, f))}

print(f"Compressed 'img' files: {len(img_files)}")
print(f"Compressed 'pic' files: {len(pic_files)}")

# 2. Gather all source code files to scan for references
src_files = []
for root, dirs, files in os.walk(base_dir):
    # Skip backup and system folders
    if any(p in root for p in ["img_backup", "pic_backup", "old img", ".git", ".idea", "node_modules"]):
        continue
    for f in files:
        if f.endswith(('.php', '.css', '.js', '.html', '.htaccess', '.sql')):
            src_files.append(os.path.join(root, f))

# Read all code content into memory
code_content = ""
for sf in src_files:
    try:
        with open(sf, "r", encoding="utf-8", errors="ignore") as file:
            code_content += "\n" + file.read()
    except Exception as e:
        print(f"Error reading {sf}: {e}")

# 3. Process img_backup
unused_img_backup = []
used_img_backup = []

for f in os.listdir(img_backup_dir):
    p = os.path.join(img_backup_dir, f)
    if not os.path.isfile(p):
        continue
    
    f_lower = f.lower()
    base_name, ext = os.path.splitext(f_lower)
    
    # Check if exists in compressed img folder (allowing extension change like .avif)
    exists_in_compressed = False
    for comp_f in img_files:
        comp_base, _ = os.path.splitext(comp_f)
        if comp_base == base_name:
            exists_in_compressed = True
            break
            
    # Check if filename is referenced in code
    is_referenced = base_name in code_content.lower()
    
    if exists_in_compressed or is_referenced:
        used_img_backup.append(f)
    else:
        unused_img_backup.append(f)

# 4. Process pic_backup
unused_pic_backup = []
used_pic_backup = []

for f in os.listdir(pic_backup_dir):
    p = os.path.join(pic_backup_dir, f)
    if not os.path.isfile(p):
        continue
    
    f_lower = f.lower()
    base_name, ext = os.path.splitext(f_lower)
    
    # Check if exists in compressed pic folder (allowing extension change like .avif)
    exists_in_compressed = False
    for comp_f in pic_files:
        comp_base, _ = os.path.splitext(comp_f)
        if comp_base == base_name:
            exists_in_compressed = True
            break
            
    # Check if filename is referenced in code
    is_referenced = base_name in code_content.lower()
    
    if exists_in_compressed or is_referenced:
        used_pic_backup.append(f)
    else:
        unused_pic_backup.append(f)

print(f"\nimg_backup results:")
print(f"  Used: {len(used_img_backup)}")
print(f"  Unused: {len(unused_img_backup)} -> {unused_img_backup}")

print(f"\npic_backup results:")
print(f"  Used: {len(used_pic_backup)}")
print(f"  Unused: {len(unused_pic_backup)} -> {unused_pic_backup}")

# Move unused files to old img folder
for f in unused_img_backup:
    src = os.path.join(img_backup_dir, f)
    dst = os.path.join(old_img_dir, "img_backup", f)
    print(f"Moving {f} to old img/img_backup...")
    shutil.move(src, dst)

for f in unused_pic_backup:
    src = os.path.join(pic_backup_dir, f)
    dst = os.path.join(old_img_dir, "pic_backup", f)
    print(f"Moving {f} to old img/pic_backup...")
    shutil.move(src, dst)

print("\n[+] Unused image backups successfully moved to 'old img' directory.")
