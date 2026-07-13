import os
import subprocess
from PIL import Image
import sys

# Ensure stdout uses UTF-8 to prevent charmap encoding errors on Windows
if sys.stdout.encoding != 'utf-8':
    try:
        sys.stdout.reconfigure(encoding='utf-8')
    except AttributeError:
        pass

base_dir = r"C:\Users\orian\code\APSI-WEB"
img_backup_dir = os.path.join(base_dir, "old img", "img_backup")
pic_backup_dir = os.path.join(base_dir, "old img", "pic_backup")

img_mobile_dir = os.path.join(base_dir, "img", "mobile")
pic_mobile_dir = os.path.join(base_dir, "pic", "mobile")

squoosh_runner = r"C:\Users\orian\code\squoosh-dev-gg\run-squoosh.js"

print("==================================================")
print("  APSI Full-Repository Mobile Image Generator")
print("==================================================")

os.makedirs(img_mobile_dir, exist_ok=True)
os.makedirs(pic_mobile_dir, exist_ok=True)

# Helper function to process directory
def process_directory(source_dir, output_dir, folder_label):
    print(f"\nScanning {folder_label} ({source_dir})...")
    for filename in os.listdir(source_dir):
        src_path = os.path.join(source_dir, filename)
        if not os.path.isfile(src_path):
            continue
            
        ext_lower = os.path.splitext(filename)[1].lower()
        if ext_lower not in ('.png', '.jpg', '.jpeg', '.avif', '.webp'):
            continue
            
        try:
            with Image.open(src_path) as img:
                width, height = img.size
        except Exception as e:
            print(f"  [-] Failed to open {filename}: {e}")
            continue
            
        basename = os.path.splitext(filename)[0]
        # Check if width is strictly greater than 1000px
        if width > 1000:
            print(f"\n[+] Processing {filename} ({width}x{height} > 1000px)...")
            
            # Squoosh CLI command to resize width to 1000px and compress to AVIF quality 50
            cmd = [
                "node",
                squoosh_runner,
                "--resize",
                '{"width":1000}',
                "--avif",
                '{"quality":50,"speed":6}',
                "-d",
                output_dir.replace("\\", "/"),
                src_path.replace("\\", "/")
            ]
            
            try:
                subprocess.run(cmd, stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True, check=True)
                dest_path = os.path.join(output_dir, basename + ".avif")
                orig_size = os.path.getsize(src_path) / 1024
                new_size = os.path.getsize(dest_path) / 1024
                print(f"  [+] Resized successfully!")
                print(f"  [+] {filename} ({orig_size:.1f} KB) -> {basename}.avif ({new_size:.1f} KB) (Saved {orig_size - new_size:.1f} KB)")
            except Exception as e:
                # Safe-encode error message just in case it contains more unmappable characters
                err_msg = str(e).encode('ascii', errors='replace').decode('ascii')
                print(f"  [-] Squoosh failed for {filename}: {err_msg}")
        else:
            print(f"  [~] Skipping {filename} ({width}x{height} <= 1000px) - Already optimized")

# Process portfolio pictures (pic_backup)
process_directory(pic_backup_dir, pic_mobile_dir, "PORTFOLIO PICTURES")

# Process layout assets (img_backup)
process_directory(img_backup_dir, img_mobile_dir, "LAYOUT ASSETS")

print("\n==================================================")
print("  [+] Repository Mobile Image Generation Complete!")
print("==================================================")
