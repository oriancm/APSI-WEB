<?php
/** Encode validated uploads as WebP, including lightweight mobile and card copies.
 * Returns the saved WebP path, or false without publishing an unconverted image.
 */
function optimizeUploadedImage($filePath, ?string $destination = null) {
    if (!function_exists('imagewebp')) return false;
    $info = @getimagesize($filePath);
    if (!$info || $info[0] * $info[1] > 20000000) return false;
    $decoders = ['image/jpeg' => 'imagecreatefromjpeg', 'image/png' => 'imagecreatefrompng', 'image/webp' => 'imagecreatefromwebp'];
    $decoder = $decoders[$info['mime']] ?? null;
    if (!$decoder || !function_exists($decoder)) return false;
    $image = @$decoder($filePath);
    if (!$image) return false;
    // Apply camera orientation before stripping metadata during encoding.
    if ($info['mime'] === 'image/jpeg' && function_exists('exif_read_data')) {
        $exif = @exif_read_data($filePath);
        $orientation = $exif['Orientation'] ?? 1;
        if (in_array($orientation, [2, 4, 5, 7], true)) imageflip($image, IMG_FLIP_HORIZONTAL);
        $angle = [3 => 180, 4 => 180, 5 => 270, 6 => 270, 7 => 90, 8 => 90][$orientation] ?? 0;
        if ($angle) { $rotated = imagerotate($image, $angle, 0); imagedestroy($image); $image = $rotated; }
    }
    $base = pathinfo($destination ?? $filePath, PATHINFO_FILENAME);
    $directory = dirname($destination ?? $filePath);
    $created = [];
    foreach ([['', 1920, 80], ['mobile', 960, 77], ['cards', 800, 75]] as [$folder, $limit, $quality]) {
        $targetDir = $directory . ($folder ? '/' . $folder : '');
        if (!is_dir($targetDir) && !@mkdir($targetDir, 0755, true)) { $ok = false; break; }
        $scale = min(1, $limit / max(imagesx($image), imagesy($image)));
        $width = max(1, (int) round(imagesx($image) * $scale));
        $height = max(1, (int) round(imagesy($image) * $scale));
        $resized = imagecreatetruecolor($width, $height);
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));
        $target = $targetDir . '/' . $base . '.webp';
        $temporary = tempnam($targetDir, 'webp-');
        $ok = $temporary && imagewebp($resized, $temporary, $quality) && filesize($temporary) > 0;
        imagedestroy($resized);
        if ($ok) $ok = rename($temporary, $target);
        if (!$ok) { if ($temporary && is_file($temporary)) unlink($temporary); break; }
        chmod($target, 0644);
        $created[] = $target;
    }
    imagedestroy($image);
    if (!$ok) { foreach ($created as $path) unlink($path); return false; }
    $result = $directory . '/' . $base . '.webp';
    if ($filePath !== $result) unlink($filePath);
    return $result;
}
