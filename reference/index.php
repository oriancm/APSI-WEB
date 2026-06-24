<?php
$path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';

if (preg_match('#^/reference/([0-9]+)/?$#', $path, $matches)) {
    $_GET['id'] = $matches[1];
    require __DIR__ . '/../reference.php';
    return;
}

http_response_code(404);
require __DIR__ . '/../index.php';
