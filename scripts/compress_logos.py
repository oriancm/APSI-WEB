import os
import subprocess
import sys

base_dir = r"C:\Users\orian\code\APSI-WEB"
logo_src_dir = os.path.join(base_dir, "old img", "img_backup", "logo")
logo_dest_dir = os.path.join(base_dir, "img", "logo")
squoosh_runner = r"C:\Users\orian\code\squoosh-dev-gg\run-squoosh.js"

print("==================================================")
print("  APSI Logo Squoosh Compressor (AVIF Quality 50)")
print("==================================================")

os.makedirs(logo_dest_dir, exist_ok=True)

if not os.path.exists(logo_src_dir):
    print(f"[-] Source directory not found: {logo_src_dir}")
    sys.exit(1)

# List all files in the source logo folder
files = os.listdir(logo_src_dir)
print(f"Found {len(files)} items in {logo_src_dir}")

for filename in files:
    src_path = os.path.join(logo_src_dir, filename)
    if not os.path.isfile(src_path):
        continue
        
    ext_lower = os.path.splitext(filename)[1].lower()
    if ext_lower not in ('.png', '.jpg', '.jpeg', '.webp'):
        print(f"[~] Skipping non-image file: {filename}")
        continue
        
    basename = os.path.splitext(filename)[0]
    print(f"\nCompressing {filename} to AVIF...")
    
    cmd = [
        "node",
        squoosh_runner,
        "--avif",
        '{"quality":50,"speed":6}',
        "-d",
        logo_dest_dir.replace("\\", "/"),
        src_path.replace("\\", "/")
    ]
    
    try:
        subprocess.run(cmd, stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True, check=True)
        dest_path = os.path.join(logo_dest_dir, basename + ".avif")
        if os.path.exists(dest_path):
            orig_size = os.path.getsize(src_path) / 1024
            new_size = os.path.getsize(dest_path) / 1024
            print(f"  [+] Compressed successfully!")
            print(f"  [+] Original: {orig_size:.1f} KB -> AVIF: {new_size:.1f} KB (Saved {orig_size - new_size:.1f} KB)")
        else:
            print(f"  [-] Expected AVIF file not found: {dest_path}")
    except Exception as e:
        err_msg = str(e).encode('ascii', errors='replace').decode('ascii')
        print(f"  [-] Squoosh failed for {filename}: {err_msg}")

print("\n[+] Logo Squoosh compression complete!")
