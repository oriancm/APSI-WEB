import os

base_dir = r"C:\Users\orian\code\APSI-WEB"

print("==================================================")
print("  APSI Render-Blocking Resource Optimizer")
print("==================================================\n")

# List of targets and replacements for each file
modifications = {
    "aboutUs.php": {
        "target": """    <link rel="stylesheet" href="/css/site.css">
    <link rel="stylesheet" href="/css/aboutUs.css?v=20260623-2">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap">""",
        "replacement": """    <!-- Fonts Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- CSS Preload -->
    <link rel="preload" href="/css/site.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="/css/aboutUs.css?v=20260623-2" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <!-- Fonts Preload -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <noscript>
        <link rel="stylesheet" href="/css/site.css">
        <link rel="stylesheet" href="/css/aboutUs.css?v=20260623-2">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap">
    </noscript>"""
    },
    
    "clients.php": {
        "target": """    <link rel="stylesheet" href="/css/site.css">
    <link rel="stylesheet" href="/css/clients.css?v=20260623-1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap">""",
        "replacement": """    <!-- Fonts Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- CSS Preload -->
    <link rel="preload" href="/css/site.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="/css/clients.css?v=20260623-1" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <!-- Fonts Preload -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <noscript>
        <link rel="stylesheet" href="/css/site.css">
        <link rel="stylesheet" href="/css/clients.css?v=20260623-1">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap">
    </noscript>"""
    },

    "contact.php": {
        "target": """    <link rel="stylesheet" href="/css/site.css">
    <link rel="stylesheet" href="/css/contact.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap">""",
        "replacement": """    <!-- Fonts Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- CSS Preload -->
    <link rel="preload" href="/css/site.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="/css/contact.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <!-- Fonts Preload -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <noscript>
        <link rel="stylesheet" href="/css/site.css">
        <link rel="stylesheet" href="/css/contact.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap">
    </noscript>"""
    },

    "index.php": {
        "target": """    <link rel="stylesheet" href="/css/site.css?v=<?= time() ?>">
    <link rel="stylesheet" href="/css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800;900&family=Inter:wght@400;500;600;700;800&display=swap">""",
        "replacement": """    <!-- Fonts Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- CSS Preload (with stable caching version instead of server-time query string to enable browser caching) -->
    <link rel="preload" href="/css/site.css?v=20260713" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="/css/style.css?v=20260713" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <!-- Fonts Preload -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800;900&family=Inter:wght@400;500;600;700;800&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <noscript>
        <link rel="stylesheet" href="/css/site.css?v=20260713">
        <link rel="stylesheet" href="/css/style.css?v=20260713">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800;900&family=Inter:wght@400;500;600;700;800&display=swap">
    </noscript>"""
    },

    "legalNotices.php": {
        "target": """    <link rel="stylesheet" href="/css/site.css">
    <link rel="stylesheet" href="/css/legalNotices.css?v=20260622-3">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap">""",
        "replacement": """    <!-- Fonts Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- CSS Preload -->
    <link rel="preload" href="/css/site.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="/css/legalNotices.css?v=20260622-3" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <!-- Fonts Preload -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <noscript>
        <link rel="stylesheet" href="/css/site.css">
        <link rel="stylesheet" href="/css/legalNotices.css?v=20260622-3">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap">
    </noscript>"""
    },

    "privacyPolicy.php": {
        "target": """    <link rel="stylesheet" href="/css/site.css">
    <link rel="stylesheet" href="/css/policyPrivacy.css?v=20260622-3">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap">""",
        "replacement": """    <!-- Fonts Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- CSS Preload -->
    <link rel="preload" href="/css/site.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="/css/policyPrivacy.css?v=20260622-3" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <!-- Fonts Preload -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <noscript>
        <link rel="stylesheet" href="/css/site.css">
        <link rel="stylesheet" href="/css/policyPrivacy.css?v=20260622-3">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap">
    </noscript>"""
    },

    "professions.php": {
        "target": """    <link rel="stylesheet" href="/css/site.css">
    <link rel="stylesheet" href="/css/professions.css?v=20260623-5">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap">""",
        "replacement": """    <!-- Fonts Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- CSS Preload -->
    <link rel="preload" href="/css/site.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="/css/professions.css?v=20260623-5" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <!-- Fonts Preload -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <noscript>
        <link rel="stylesheet" href="/css/site.css">
        <link rel="stylesheet" href="/css/professions.css?v=20260623-5">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap">
    </noscript>"""
    },

    "reference.php": {
        "target": """    <link rel="stylesheet" href="/css/site.css">
    <link rel="stylesheet" href="/css/reference.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap">""",
        "replacement": """    <!-- Fonts Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- CSS Preload (with stable caching version instead of server-time query string to enable browser caching) -->
    <link rel="preload" href="/css/site.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="/css/reference.css?v=20260713" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <!-- Fonts Preload -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <noscript>
        <link rel="stylesheet" href="/css/site.css">
        <link rel="stylesheet" href="/css/reference.css?v=20260713">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap">
    </noscript>"""
    },

    "references.php": {
        "target": """    <link rel="stylesheet" href="/css/site.css">
    <link rel="stylesheet" href="/css/references.css?v=20260623-1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap">""",
        "replacement": """    <!-- Fonts Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- CSS Preload -->
    <link rel="preload" href="/css/site.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="/css/references.css?v=20260623-1" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <!-- Fonts Preload -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <noscript>
        <link rel="stylesheet" href="/css/site.css">
        <link rel="stylesheet" href="/css/references.css?v=20260623-1">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap">
    </noscript>"""
    },

    "mailSent.php": {
        "target": """    <link rel="stylesheet" href="css/mailSent.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:bold">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">""",
        "replacement": """    <!-- Fonts Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- CSS Preload -->
    <link rel="preload" href="css/mailSent.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <!-- Fonts Preload -->
    <link rel="preload" href="https://fonts.googleapis.com/css?family=Montserrat:bold" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <noscript>
        <link rel="stylesheet" href="css/mailSent.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:bold">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    </noscript>"""
    }
}

# Also handle the trailing styleGlobalNotIndex.css preload in mailSent.php
mail_sent_path = os.path.join(base_dir, "mailSent.php")
with open(mail_sent_path, "r", encoding="utf-8", errors="ignore") as f:
    mail_sent_content = f.read()

target_global_css = '<link rel="stylesheet" href="/css/styleGlobalNotIndex.css">'
replacement_global_css = """<!-- CSS Preload -->
<link rel="preload" href="/css/styleGlobalNotIndex.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="/css/styleGlobalNotIndex.css"></noscript>"""

if target_global_css in mail_sent_content:
    mail_sent_content = mail_sent_content.replace(target_global_css, replacement_global_css)
    with open(mail_sent_path, "w", encoding="utf-8") as f:
        f.write(mail_sent_content)
    print("  [+] Successfully updated styleGlobalNotIndex.css in mailSent.php")

# Apply modifications for each file
success_count = 0
for filename, mod in modifications.items():
    filepath = os.path.join(base_dir, filename)
    if not os.path.exists(filepath):
        print(f"  [-] Skip {filename}: File not found")
        continue
        
    with open(filepath, "r", encoding="utf-8", errors="ignore") as f:
        content = f.read()
        
    target_clean = "\n".join([line.strip() for line in mod["target"].strip().split("\n")])
    
    # Let's perform a slightly flexible find to account for indentation
    found = False
    
    # Try exact match first
    if mod["target"] in content:
        content = content.replace(mod["target"], mod["replacement"])
        found = True
    else:
        # Try finding line by line (or with varying indentation)
        # Let's do a regex replacement or just strip leading spaces for matching
        lines = content.split("\n")
        target_lines = mod["target"].strip().split("\n")
        
        # Check if we can find consecutive lines that match stripped versions
        for start_idx in range(len(lines) - len(target_lines) + 1):
            match = True
            for offset in range(len(target_lines)):
                if target_lines[offset].strip() not in lines[start_idx + offset].strip():
                    match = False
                    break
            if match:
                # We found a match! Replace those lines
                # Capture the indentation of the first line to format the replacement nicely
                orig_first_line = lines[start_idx]
                indent = orig_first_line[:len(orig_first_line) - len(orig_first_line.lstrip())]
                
                # Format replacement with the same indentation
                formatted_replacement = "\n".join([indent + l if l.strip() else l for l in mod["replacement"].split("\n")])
                
                # Replace the slice of lines with our formatted replacement
                lines[start_idx:start_idx + len(target_lines)] = [formatted_replacement]
                content = "\n".join(lines)
                found = True
                break
                
    if found:
        with open(filepath, "w", encoding="utf-8") as f:
            f.write(content)
        print(f"  [+] Successfully optimized render-blocking resources in {filename}")
        success_count += 1
    else:
        print(f"  [-] Failed to find target block in {filename}")

print(f"\n[+] Completed render-blocking optimization for {success_count} files.")

# Re-run performance audit to confirm everything still works and generate fresh report
print("\nRe-running performance audit script to update report...")
try:
    import subprocess
    audit_script = os.path.join(base_dir, "scripts", "performance_test.php")
    subprocess.run(["php", audit_script], check=True)
    print("[+] Performance report successfully regenerated!")
    
    # Copy the updated report as an interactive Gemini artifact
    shutil_dest = r"C:\Users\orian\.gemini\antigravity-cli\brain\8986a8ed-8b58-4e15-95e8-abc65f40783a\performance_audit_results.md"
    import shutil
    shutil.copy2(os.path.join(base_dir, "PERFORMANCE_REPORT.md"), shutil_dest)
    print("[+] Interactive performance audit artifact updated.")
except Exception as e:
    print(f"[-] Failed to run performance test or copy artifact: {e}")
