import os
import re

base_dir = r"C:\Users\orian\code\APSI-WEB"
img_dir = os.path.join(base_dir, "img")

print("==================================================")
print("  APSI Code-Image Reconciliation Tool")
print("==================================================\n")

# Get list of all physical files in /img/ recursively
physical_img_files = {}
for root, dirs, files in os.walk(img_dir):
    for f in files:
        full_path = os.path.join(root, f)
        rel_path = os.path.relpath(full_path, base_dir).replace("\\", "/")
        base_name, ext = os.path.splitext(f.lower())
        
        # Key is lowercase basename (e.g., "logo-nav"), value is correct relative path "img/logo-nav.png"
        # We also store keys like "logo/citadis" if inside subfolders
        sub_folder = os.path.relpath(root, img_dir).replace("\\", "/")
        if sub_folder == ".":
            key = base_name
        else:
            key = f"{sub_folder}/{base_name}".lower()
            
        physical_img_files[key] = f"img/{sub_folder}/{f}" if sub_folder != "." else f"img/{f}"

print("Found physical image mappings in /img/:")
for k, v in physical_img_files.items():
    print(f"  {k} => {v}")

# Gather all code files
code_files = []
for root, dirs, files in os.walk(base_dir):
    if any(p in root for p in ["old img", ".git", ".idea", "node_modules", "scripts", "bdd"]):
        continue
    for f in files:
        if f.endswith(('.php', '.css', '.js', '.html')):
            code_files.append(os.path.join(root, f))

# We look for references like /img/something.ext or img/something.ext
# Let's perform a regex find and replace for images in /img/
img_ref_pattern = re.compile(r'/?img/([^"\'\s)]+\.(?:avif|png|jpg|jpeg|gif|svg))', re.IGNORECASE)

updates_made = 0

for filepath in code_files:
    with open(filepath, "r", encoding="utf-8", errors="ignore") as f:
        content = f.read()
        
    def replace_func(match):
        global updates_made
        full_match = match.group(0) # e.g. "/img/logo-nav.avif"
        img_path_part = match.group(1) # e.g. "logo-nav.avif"
        
        # Get basename and subfolder if any
        img_path_clean = img_path_part.split('?')[0] # remove query params
        base_with_sub, ext = os.path.splitext(img_path_clean.lower())
        
        # Look up in our physical files map
        if base_with_sub in physical_img_files:
            correct_rel_path = physical_img_files[base_with_sub]
            # Maintain leading slash if it was present
            has_leading_slash = full_match.startswith('/')
            correct_full_match = ("/" if has_leading_slash else "") + correct_rel_path
            
            if full_match.lower() != correct_full_match.lower():
                print(f"  [~] Mismatch in {os.path.basename(filepath)}: '{full_match}' -> '{correct_full_match}'")
                updates_made += 1
                return correct_full_match
        
        return full_match

    new_content = img_ref_pattern.sub(replace_func, content)
    
    if new_content != content:
        with open(filepath, "w", encoding="utf-8") as f:
            f.write(new_content)

print(f"\n[+] Image reconciliation complete! Made {updates_made} updates across codebase.")
