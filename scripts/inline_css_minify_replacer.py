import os

base_dir = r"C:\Users\orian\code\APSI-WEB"

pages = [
    "index.php",
    "aboutUs.php",
    "clients.php",
    "contact.php",
    "legalNotices.php",
    "privacyPolicy.php",
    "professions.php",
    "reference.php",
    "references.php",
    "mailSent.php"
]

minify_helper_code = """    <?php
    if (!function_exists('apsi_minify_css')) {
        function apsi_minify_css($path) {
            if (!file_exists($path)) return '';
            $css = file_get_contents($path);
            // Remove CSS comments
            $css = preg_replace('!/\\*[^*]*\\*+([^/*][^*]*\\*+)*/!', '', $css);
            // Remove space around braces, colons, semi-colons
            $css = str_replace(array("\\r\\n", "\\r", "\\n", "\\t", '  ', '    '), '', $css);
            $css = preg_replace('/(\\s*([:;{}])\\s*)/', '$2', $css);
            return $css;
        }
    }
    ?>"""

print("==================================================")
print("  APSI Dynamic PHP CSS Minifier Replacer")
print("==================================================")

for page in pages:
    page_path = os.path.join(base_dir, page)
    if not os.path.exists(page_path):
        print(f"[-] Page not found: {page}")
        continue
        
    print(f"Processing {page}...")
    with open(page_path, 'r', encoding='utf-8') as f:
        content = f.read()
        
    # Find the <style>...</style> block
    start_tag = "<style>"
    end_tag = "</style>"
    
    start_idx = content.find(start_tag)
    end_idx = content.find(end_tag)
    
    if start_idx != -1 and end_idx != -1 and start_idx < end_idx:
        style_block = content[start_idx : end_idx + len(end_tag)]
        
        # Parse CSS files being inlined
        lines = style_block.splitlines()
        css_files = []
        for line in lines:
            if "include __DIR__" in line:
                # Extract the relative path, e.g. /css/site.css
                parts = line.split("'")
                if len(parts) >= 3:
                    css_files.append(parts[1])
                else:
                    parts = line.split('"')
                    if len(parts) >= 3:
                        css_files.append(parts[1])
                        
        if css_files:
            # Build the new minified style block
            new_style_block = "    <style>\n"
            for css_file in css_files:
                new_style_block += f"        <?= apsi_minify_css(__DIR__ . '{css_file}'); ?>\n"
            new_style_block += "    </style>"
            
            # Combine helper code and the new style block
            full_replacement = minify_helper_code + "\n" + new_style_block
            
            # Replace the old style block in content
            new_content = content.replace(style_block, full_replacement)
            
            with open(page_path, 'w', encoding='utf-8') as f_out:
                f_out.write(new_content)
            print(f"  [+] Successfully updated with dynamic PHP CSS minifier! Files: {css_files}")
        else:
            print(f"  [-] No included CSS files found in style block of {page}.")
    else:
        print(f"  [-] Style block not found in {page}.")

print("\n[+] Dynamic CSS minifier successfully installed across all page templates!")
