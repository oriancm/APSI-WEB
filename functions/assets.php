<?php
/** Fingerprints change whenever an asset changes, allowing safe browser caching. */
function apsiAsset(string $path): string {
    $file = __DIR__ . '/..' . $path;
    $minPath = preg_replace('/(?<!\.min)\.(css|js)$/', '.min.$1', $path);
    $minFile = __DIR__ . '/..' . $minPath;
    if ($minPath !== $path && is_file($minFile) && filemtime($minFile) >= filemtime($file)) {
        $path = $minPath;
        $file = $minFile;
    }
    return htmlspecialchars($path . '?v=' . (is_file($file) ? filemtime($file) : '1'), ENT_QUOTES, 'UTF-8');
}
