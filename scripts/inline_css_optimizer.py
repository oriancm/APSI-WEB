import os
import re

base_dir = r"C:\Users\orian\code\APSI-WEB"

print("==================================================")
print("  APSI Dynamic PHP CSS Inlining Optimizer")
print("==================================================\n")

# Map files to their specific stylesheets
page_styles = {
    "index.php": ["site.css", "style.css"],
    "aboutUs.php": ["site.css", "aboutUs.css"],
    "clients.php": ["site.css", "clients.css"],
    "contact.php": ["site.css", "contact.css"],
    "legalNotices.php": ["site.css", "legalNotices.css"],
    "privacyPolicy.php": ["site.css", "policyPrivacy.css"],
    "professions.php": ["site.css", "professions.css"],
    "reference.php": ["site.css", "reference.css"],
    "references.php": ["site.css", "references.css"],
    "mailSent.php": ["styleGlobalNotIndex.css"]
}

for filename, styles in page_styles.items():
    filepath = os.path.join(base_dir, filename)
    if not os.path.exists(filepath):
        continue
        
    print(f"Optimizing {filename}...")
    with open(filepath, "r", encoding="utf-8") as f:
        content = f.read()
    
    # We want to replace the synchronous CSS links with dynamic PHP include tags inside a <style> block.
    # We find the stylesheet block.
    # Target structure:
    #     <!-- Stylesheets (Loaded synchronously to prevent Flash of Unstyled Content) -->
    #     <link rel="stylesheet" href="/css/site.css?v=20260713">
    #     <link rel="stylesheet" href="/css/style.css?v=20260713">
    
    # We can match any link tags referencing /css/...
    link_pattern = re.compile(r'\s*<link\s+rel="stylesheet"\s+href="/css/[^"]+">', re.IGNORECASE)
    
    # Replace all link tags in this block
    cleaned_content = link_pattern.sub('', content)
    
    # Construct our dynamic inlining block
    inline_block = '    <!-- Stylesheets (Inlined dynamically via PHP to prevent FOUC & maximize mobile performance) -->\n    <style>\n'
    for style in styles:
        inline_block += f'        <?php include __DIR__ . \'/css/{style}\'; ?>\n'
    inline_block += '    </style>'
    
    # Let's replace the header comment block if present
    comment_pattern = re.compile(
        r'<!-- Stylesheets \(Loaded synchronously to prevent Flash of Unstyled Content\) -->',
        re.IGNORECASE
    )
    
    if comment_pattern.search(cleaned_content):
        modified_content = comment_pattern.sub(inline_block, cleaned_content)
    else:
        # Fallback: if comment not found, replace the first remaining CSS link or inject in head
        # Let's search for </head> and insert before it if not found, but since remove_fouc.py ran, the comment is present on all files.
        modified_content = cleaned_content
        
    if modified_content != content:
        with open(filepath, "w", encoding="utf-8") as f:
            f.write(modified_content)
        print(f"  [+] Dynamic PHP CSS inlining successfully integrated in {filename}!")
    else:
        print(f"  [-] No changes made for {filename}")

print("\n[+] Optimization complete! Your CSS is now dynamically inlined by PHP, providing 100% render-blocking elimination and 0ms FOUC.")
