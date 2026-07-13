import os
import subprocess

base_dir = r"C:\Users\orian\code\APSI-WEB"
pic_backup_dir = os.path.join(base_dir, "old img", "pic_backup")
mobile_dir = os.path.join(base_dir, "pic", "mobile")

squoosh_runner = r"C:\Users\orian\code\squoosh-dev-gg\run-squoosh.js"

print("==================================================")
print("  APSI Pristine Mobile Responsive Image Generator")
print("==================================================")

# Create output folder
os.makedirs(mobile_dir, exist_ok=True)

# List of base names and their original extensions in "old img/pic_backup"
images_to_resize = {
    "amenagement_de_la_place_jean_jaures_1": ".jpg",
    "l_hotel_des_monnaies_niel_1": ".jpg",
    "residence_les_angevines_1": ".jpg",
    "centre_culturel_simone_signoret_1": ".jpg",
    "residence_l_aygues_1": ".jpg"
}

for basename, ext in images_to_resize.items():
    src_filename = basename + ext
    src_path = os.path.join(pic_backup_dir, src_filename)
    if not os.path.exists(src_path):
        print(f"[-] Source original file not found: {src_filename}")
        continue
        
    print(f"\nProcessing original {src_filename}...")
    
    # Resize pristine high-resolution image to width 1000px, 
    # compressing directly to AVIF (quality 50) for maximum quality and speed
    cmd = [
        "node",
        squoosh_runner,
        "--resize",
        '{"width":1000}',
        "--avif",
        '{"quality":50,"speed":6}',
        "-d",
        mobile_dir.replace("\\", "/"),
        src_path.replace("\\", "/")
    ]
    
    try:
        res = subprocess.run(cmd, stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True, check=True)
        dest_path = os.path.join(mobile_dir, basename + ".avif")
        orig_size = os.path.getsize(src_path) / 1024
        new_size = os.path.getsize(dest_path) / 1024
        print(f"  [+] Resized and compressed pristine source successfully!")
        print(f"  [+] Original High-Res: {orig_size:.1f} KB -> Mobile: {new_size:.1f} KB (Saved {orig_size - new_size:.1f} KB)")
    except Exception as e:
        print(f"  [-] Squoosh failed for {src_filename}: {e}")

print("\n[+] Mobile images generation finished!")
