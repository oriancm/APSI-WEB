import os
import subprocess

base_dir = r"C:\Users\orian\code\APSI-WEB"
pic_dir = os.path.join(base_dir, "pic")
mobile_dir = os.path.join(pic_dir, "mobile")

squoosh_runner = r"C:\Users\orian\code\squoosh-dev-gg\run-squoosh.js"

print("==================================================")
print("  APSI Mobile Responsive Image Generator")
print("==================================================")

# Create output folder
os.makedirs(mobile_dir, exist_ok=True)

# List of images to scale down for devices under 980px
images_to_resize = [
    "amenagement_de_la_place_jean_jaures_1.avif",
    "l_hotel_des_monnaies_niel_1.avif",
    "residence_les_angevines_1.avif",
    "centre_culturel_simone_signoret_1.avif",
    "residence_l_aygues_1.avif"
]

for filename in images_to_resize:
    src_path = os.path.join(pic_dir, filename)
    if not os.path.exists(src_path):
        print(f"[-] Source not found: {filename}")
        continue
        
    print(f"\nProcessing {filename}...")
    
    # We resize to width 1000px which is a perfect fit for devices under 980px,
    # ensuring crisp rendering on high-DPI screens while dropping size by 80%+
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
        dest_path = os.path.join(mobile_dir, filename)
        orig_size = os.path.getsize(src_path) / 1024
        new_size = os.path.getsize(dest_path) / 1024
        print(f"  [+] Resized successfully!")
        print(f"  [+] Original: {orig_size:.1f} KB -> Mobile: {new_size:.1f} KB (Saved {orig_size - new_size:.1f} KB)")
    except Exception as e:
        print(f"  [-] Squoosh failed for {filename}: {e}")

print("\n[+] Mobile images generation finished!")
