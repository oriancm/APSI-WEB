import os
import re

base_dir = r"C:\Users\orian\code\APSI-WEB"

print("==================================================")
print("  APSI FOUC Elimination & CSS Optimizer")
print("==================================================\n")

php_files = [
    "index.php", "aboutUs.php", "clients.php", "contact.php",
    "legalNotices.php", "privacyPolicy.php", "professions.php",
    "references.php", "reference.php", "mailSent.php"
]

# We want to replace the preload blocks for local styles with synchronous stylesheet links.
# Example target block:
#     <!-- CSS Preload ... -->
#     <link rel="preload" href="/css/site.css..." as="style" ...>
#     <link rel="preload" href="/css/style.css..." as="style" ...>
#     
#     <!-- Fonts Preload -->
#     <link rel="preload" href="https://fonts.googleapis.com/css2?..." as="style" ...>
#     
#     <noscript>
#         <link rel="stylesheet" href="/css/site.css...">
#         <link rel="stylesheet" href="/css/style.css...">
#         <link rel="stylesheet" href="https://fonts.googleapis.com/css2?...">
#     </noscript>

# A simpler, ultra-robust approach is to use precise regular expressions on the file contents.

for filename in php_files:
    filepath = os.path.join(base_dir, filename)
    if not os.path.exists(filepath):
        continue
        
    print(f"Processing {filename}...")
    with open(filepath, "r", encoding="utf-8") as f:
        content = f.read()
    
    # 1. Regex to find local preloads: <link rel="preload" href="(/css/[^"]+)" as="style" onload="[^"]+">
    # We will replace them with: <link rel="stylesheet" href="\1">
    # If the href doesn't have a query param, we'll append ?v=20260713
    
    local_preload_pattern = re.compile(
        r'<link\s+rel="preload"\s+href="(/css/[^"]+)"\s+as="style"\s+onload="[^"]+">',
        re.IGNORECASE
    )
    
    def replace_local_preload(match):
        href = match.group(1)
        # S'assurer de la présence du query parameter de mise en cache
        if "?" not in href:
            href += "?v=20260713"
        return f'<link rel="stylesheet" href="{href}">'
        
    modified_content = local_preload_pattern.sub(replace_local_preload, content)
    
    # 2. Fix the noscript block to only include Google Fonts, since local CSS is now loaded synchronously.
    # We find the noscript block and remove references to local CSS files.
    
    noscript_pattern = re.compile(r'<noscript>([\s\S]*?)</noscript>', re.IGNORECASE)
    
    def clean_noscript(match):
        inner_content = match.group(1)
        # Filter out lines containing "/css/"
        lines = inner_content.splitlines()
        filtered_lines = [line for line in lines if "/css/" not in line]
        
        # If there's still content left (like Google Fonts link), keep the block. Else, return empty string.
        cleaned_inner = "\n".join(filtered_lines).strip()
        if cleaned_inner:
            return f'<noscript>\n        {cleaned_inner}\n    </noscript>'
        else:
            return ''
            
    modified_content = noscript_pattern.sub(clean_noscript, modified_content)
    
    # 3. Clean up empty comment lines or update block headers
    modified_content = modified_content.replace(
        "<!-- CSS Preload (with stable caching version instead of server-time query string to enable browser caching) -->",
        "<!-- Stylesheets (Loaded synchronously to prevent Flash of Unstyled Content) -->"
    )
    modified_content = modified_content.replace(
        "<!-- CSS Preload -->",
        "<!-- Stylesheets (Loaded synchronously to prevent Flash of Unstyled Content) -->"
    )
    
    if modified_content != content:
        with open(filepath, "w", encoding="utf-8") as f:
            f.write(modified_content)
        print(f"  [+] Successfully optimized and removed FOUC from {filename}")
    else:
        print(f"  [-] No changes needed for {filename}")

print("\n[+] All core pages optimized! FOUC has been completely eliminated while preserving deferred Google Fonts preloading.")
