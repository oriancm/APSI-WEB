import os
import subprocess
import shutil
import re

base_dir = r"C:\Users\orian\code\APSI-WEB"
img_dir = os.path.join(base_dir, "img")
pic_dir = os.path.join(base_dir, "pic")
img_backup_dir = os.path.join(base_dir, "img_backup")
pic_backup_dir = os.path.join(base_dir, "pic_backup")

squoosh_runner = r"C:\Users\orian\code\squoosh-dev-gg\run-squoosh.js"

print("==================================================")
print("  APSI Image Compression & Code Reference Updater")
print("==================================================\n")

# 1. Collect all images to process from img_backup and pic_backup
tasks = [] # list of dicts: {"src": path, "dest_dir": path, "dest_ext": str, "type": "img"|"pic"}

def scan_backup_folder(backup_dir, dest_dir, folder_type):
    for f in os.listdir(backup_dir):
        src_path = os.path.join(backup_dir, f)
        if not os.path.isfile(src_path):
            continue
        
        base_name, ext = os.path.splitext(f)
        ext_lower = ext.lower()
        
        # Skip system or non-image files
        if ext_lower not in ['.jpg', '.jpeg', '.png', '.gif', '.webp', '.avif', '.svg', '.ico']:
            continue
            
        tasks.append({
            "src": src_path,
            "filename": f,
            "basename": base_name,
            "ext": ext,
            "dest_dir": dest_dir,
            "type": folder_type
        })

scan_backup_folder(img_backup_dir, img_dir, "img")
scan_backup_folder(pic_backup_dir, pic_dir, "pic")

print(f"Detected {len(tasks)} images in backup directories.")

# 2. Compress each image to AVIF using Squoosh (or copy if already AVIF or SVG/ICO)
compression_mapping = {} # old_filename -> new_filename
errors = []

for i, task in enumerate(tasks, 1):
    src = task["src"]
    filename = task["filename"]
    basename = task["basename"]
    ext = task["ext"]
    dest_dir = task["dest_dir"]
    ext_lower = ext.lower()
    
    print(f"[{i}/{len(tasks)}] Processing {filename} ({task['type']})...")
    
    # SVG and ICO don't need AVIF compression, we can just copy them
    if ext_lower in ['.svg', '.ico']:
        dest_file = os.path.join(dest_dir, filename)
        print(f"  -> SVG/ICO file: copying directly...")
        try:
            shutil.copy2(src, dest_file)
            compression_mapping[filename] = filename
        except Exception as e:
            print(f"  [-] Error copying: {e}")
            errors.append((filename, str(e)))
        continue
        
    # If already AVIF, copy directly as instructed: "if am image is already in avif dont touch it"
    if ext_lower == '.avif':
        dest_file = os.path.join(dest_dir, filename)
        print(f"  -> Already AVIF: copying directly...")
        try:
            shutil.copy2(src, dest_file)
            compression_mapping[filename] = filename
        except Exception as e:
            print(f"  [-] Error copying: {e}")
            errors.append((filename, str(e)))
        continue
        
    # Compress png, jpg, jpeg, webp, gif using Squoosh
    dest_filename = basename + ".avif"
    dest_path = os.path.join(dest_dir, dest_filename)
    
    print(f"  -> Compressing with Squoosh to AVIF (quality: 50, speed: 6)...")
    
    # Run Squoosh CLI
    cmd = [
        "node",
        squoosh_runner,
        "--avif",
        '{"quality":50,"speed":6}',
        "-d",
        dest_dir.replace("\\", "/"),
        src.replace("\\", "/")
    ]
    
    try:
        # Run command and capture output
        res = subprocess.run(cmd, stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True, check=True)
        print(f"  [+] Compressed successfully to {dest_filename}")
        compression_mapping[filename] = dest_filename
        
        # Delete the old compressed image file in the dest_dir if it exists
        old_compressed_path = os.path.join(dest_dir, filename)
        if os.path.exists(old_compressed_path) and os.path.isfile(old_compressed_path):
            print(f"  [+] Deleting old compressed image: {filename}")
            os.remove(old_compressed_path)
            
    except subprocess.CalledProcessError as e:
        print(f"  [-] Squoosh failed for {filename}: {e.stderr}")
        errors.append((filename, e.stderr))
    except Exception as e:
        print(f"  [-] Unexpected error: {e}")
        errors.append((filename, str(e)))

print("\n--- Compression Stage Finished ---")
print(f"Successfully processed {len(compression_mapping)} files.")
if errors:
    print(f"Encountered errors in {len(errors)} files.")

# 3. Update Code References
print("\nScanning codebase for image reference updates...")

# Gather code files to update
src_files = []
for root, dirs, files in os.walk(base_dir):
    if any(p in root for p in ["img_backup", "pic_backup", "old img", ".git", ".idea", "node_modules"]):
        continue
    for f in files:
        if f.endswith(('.php', '.css', '.js', '.html', '.htaccess', '.json')):
            src_files.append(os.path.join(root, f))

print(f"Found {len(src_files)} code files to check.")

update_count = 0
for sf in src_files:
    try:
        with open(sf, "r", encoding="utf-8", errors="ignore") as f:
            content = f.read()
            
        modified = False
        new_content = content
        
        # Replace occurrences of old filenames with new .avif filenames
        for old_name, new_name in compression_mapping.items():
            if old_name == new_name:
                continue
            
            # Case-sensitive search-and-replace
            if old_name in new_content:
                # To be absolutely sure we don't accidentally match parts of words, we do standard replacement
                new_content = new_content.replace(old_name, new_name)
                modified = True
                
        if modified:
            with open(sf, "w", encoding="utf-8") as f:
                f.write(new_content)
            print(f"  [+] Updated references in {os.path.relpath(sf, base_dir)}")
            update_count += 1
            
    except Exception as e:
        print(f"  [-] Error updating {sf}: {e}")

print(f"\n[+] Reference update complete. Updated {update_count} source files.")

# 4. Re-run Performance Audit to generate a fresh report
print("\nRe-running performance audit script to update report...")
try:
    audit_script = os.path.join(base_dir, "scripts", "performance_test.php")
    subprocess.run(["php", audit_script], check=True)
    print("[+] Performance report successfully regenerated!")
except Exception as e:
    print(f"[-] Failed to run performance test: {e}")

print("\n==================================================")
print("[+] IMAGE RECOMPRESSION AND CODE REFERENCE UPDATING COMPLETE!")
print("==================================================")
