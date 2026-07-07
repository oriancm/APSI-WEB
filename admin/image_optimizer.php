<?php
/**
 * APSI-WEB Image Optimizer Utility
 * Automatically compresses and generates desktop/mobile dual-path reference images
 */

function optimizeUploadedImage($filePath) {
    if (!file_exists($filePath)) {
        return false;
    }

    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    $allowed_exts = ['jpg', 'jpeg', 'png', 'webp'];
    if (!in_array($ext, $allowed_exts)) {
        return false; // Skip if not a supported format
    }

    // 1. Create backup of original image in pic_backup/
    $projectRoot = dirname(__DIR__);
    $backupDir = $projectRoot . DIRECTORY_SEPARATOR . 'pic_backup';
    if (!is_dir($backupDir)) {
        mkdir($backupDir, 0755, true);
    }
    
    $filename = basename($filePath);
    $backupPath = $backupDir . DIRECTORY_SEPARATOR . $filename;
    copy($filePath, $backupPath); // Safe backup!

    // 2. Load image based on extension
    $src_image = null;
    switch ($ext) {
        case 'jpg':
        case 'jpeg':
            if (function_exists('imagecreatefromjpeg')) {
                $src_image = @imagecreatefromjpeg($filePath);
            }
            break;
        case 'png':
            if (function_exists('imagecreatefrompng')) {
                $src_image = @imagecreatefrompng($filePath);
            }
            break;
        case 'webp':
            if (function_exists('imagecreatefromwebp')) {
                $src_image = @imagecreatefromwebp($filePath);
            }
            break;
    }

    if (!$src_image) {
        return false; // Could not load image
    }

    // 3. Rotate image based on EXIF if JPEG
    if (($ext === 'jpg' || $ext === 'jpeg') && function_exists('exif_read_data')) {
        $exif = @exif_read_data($backupPath);
        if ($exif && isset($exif['Orientation'])) {
            $ort = $exif['Orientation'];
            switch ($ort) {
                case 3:
                    $src_image = imagerotate($src_image, 180, 0);
                    break;
                case 6:
                    $src_image = imagerotate($src_image, -90, 0);
                    break;
                case 8:
                    $src_image = imagerotate($src_image, 90, 0);
                    break;
            }
        }
    }

    // 4. Save Mobile/Card Thumbnail version (Max 800px, Quality 50)
    $cardDir = $projectRoot . DIRECTORY_SEPARATOR . 'pic' . DIRECTORY_SEPARATOR . 'card';
    if (!is_dir($cardDir)) {
        mkdir($cardDir, 0755, true);
    }
    $cardPath = $cardDir . DIRECTORY_SEPARATOR . $filename;
    
    $card_image = resizeGDImage($src_image, 800);
    saveGDImage($card_image, $cardPath, $ext, 50, true);
    if ($card_image !== $src_image) {
        imagedestroy($card_image);
    }

    // 5. Save Desktop/Carousel version (Max 1920px, Quality 70)
    $full_image = resizeGDImage($src_image, 1920);
    saveGDImage($full_image, $filePath, $ext, 70, false);
    if ($full_image !== $src_image) {
        imagedestroy($full_image);
    }

    imagedestroy($src_image);
    return true;
}

/**
 * Resizes a GD image preserving aspect ratio if it exceeds max size.
 */
function resizeGDImage($src_image, $max_size) {
    $width = imagesx($src_image);
    $height = imagesy($src_image);

    if ($width <= $max_size && $height <= $max_size) {
        return $src_image; // No resizing needed
    }

    if ($width > $height) {
        $new_width = $max_size;
        $new_height = intval($height * ($max_size / $width));
    } else {
        $new_height = $max_size;
        $new_width = intval($width * ($max_size / $height));
    }

    $dst_image = imagecreatetruecolor($new_width, $new_height);

    // Keep transparency
    imagealphablending($dst_image, false);
    imagesavealpha($dst_image, true);

    imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    return $dst_image;
}

/**
 * Saves GD image to disk with optimized quality and formats
 */
function saveGDImage($image, $path, $ext, $quality, $is_card = false) {
    switch ($ext) {
        case 'jpg':
        case 'jpeg':
            imagejpeg($image, $path, $quality);
            break;
        case 'png':
            // For mobile cards, we can convert to palette to save massive space
            if ($is_card) {
                @imagetruecolortopalette($image, true, 128);
            }
            // PNG quality in GD is 0 (no compression) to 9 (max compression)
            imagepng($image, $path, 9);
            break;
        case 'webp':
            imagewebp($image, $path, $quality);
            break;
    }
}
