<?php
/** Resolve existing database filenames to their optimized WebP counterpart. */
function apsiWebpPhotos(array $photos): array {
    foreach ($photos as &$photo) {
        $name = pathinfo(basename($photo['titre']), PATHINFO_FILENAME) . '.webp';
        if (is_file(__DIR__ . '/../pic/' . $name)) $photo['titre'] = $name;
    }
    unset($photo);
    return $photos;
}
function apsiCardImage(string $name): string {
    $card = 'cards/' . pathinfo(basename($name), PATHINFO_FILENAME) . '.webp';
    return is_file(__DIR__ . '/../pic/' . $card) ? $card : $name;
}

/** Intrinsic dimensions reserve space before a project photograph arrives. */
function apsiImageSize(string $name): string {
    $file = __DIR__ . '/../pic/' . $name;
    $size = is_file($file) ? @getimagesize($file) : false;
    return $size ? 'width="' . (int)$size[0] . '" height="' . (int)$size[1] . '"' : '';
}
