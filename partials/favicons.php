<?php
// A content version refreshes browser icons when the logo changes.
$faviconPath = __DIR__ . '/../favicon-32x32.png';
$faviconVersion = is_file($faviconPath) ? substr(hash_file('sha256', $faviconPath), 0, 12) : 'apsi-color';
?>
<link rel="icon" type="image/x-icon" href="/favicon.ico?v=<?= $faviconVersion ?>">
<?php foreach ([16, 32, 48, 96] as $faviconSize): ?>
<link rel="icon" type="image/png" sizes="<?= $faviconSize ?>x<?= $faviconSize ?>" href="/favicon-<?= $faviconSize ?>x<?= $faviconSize ?>.png?v=<?= $faviconVersion ?>">
<?php endforeach; ?>
<link rel="icon" type="image/png" sizes="192x192" href="/android-chrome-192x192.png?v=<?= $faviconVersion ?>">
<link rel="icon" type="image/png" sizes="512x512" href="/android-chrome-512x512.png?v=<?= $faviconVersion ?>">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png?v=<?= $faviconVersion ?>">
<link rel="manifest" href="/site.webmanifest?v=<?= $faviconVersion ?>">
