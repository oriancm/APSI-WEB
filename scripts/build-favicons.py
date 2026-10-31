"""Generate browser/app icons from APSI's original color logo, without redrawing it."""
from hashlib import sha256
import json
from pathlib import Path

from PIL import Image

root = Path(__file__).resolve().parents[1]
source = Image.open(root / 'img/logo-nav-transparent.png').convert('RGBA')
source = source.crop(source.getbbox())
source.thumbnail((448, 448), Image.Resampling.LANCZOS)
canvas = Image.new('RGBA', (512, 512), 'white')
canvas.alpha_composite(source, ((512 - source.width) // 2, (512 - source.height) // 2))
outputs = {
    16: 'favicon-16x16.png', 32: 'favicon-32x32.png', 48: 'favicon-48x48.png',
    96: 'favicon-96x96.png', 180: 'apple-touch-icon.png',
    192: 'android-chrome-192x192.png', 512: 'android-chrome-512x512.png',
}
for size, name in outputs.items():
    canvas.resize((size, size), Image.Resampling.LANCZOS).save(root / name, optimize=True)
canvas.save(root / 'favicon.ico', sizes=[(size, size) for size in [16, 24, 32, 48, 64, 128, 256]])
version = sha256((root / 'favicon-32x32.png').read_bytes()).hexdigest()[:12]
manifest = json.loads((root / 'site.webmanifest').read_text(encoding='utf-8'))
manifest['theme_color'] = '#0067d6'
for icon in manifest['icons']:
    icon['src'] = icon['src'].split('?')[0] + '?v=' + version
(root / 'site.webmanifest').write_text(json.dumps(manifest, ensure_ascii=False, indent=2) + '\n', encoding='utf-8')
print('Built APSI color favicon (multi-size ICO), PNG icons and versioned web manifest.')
