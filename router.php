<?php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$localFile = __DIR__ . $path;

if ($path !== '/' && is_file($localFile)) {
    return false;
}

if ($path === '/') {
    require __DIR__ . '/index.php';
    return true;
}

$cleanPath = trim($path, '/');

if (preg_match('#^reference/([0-9]+)$#', $cleanPath, $matches)) {
    $_GET['id'] = $matches[1];
    require __DIR__ . '/reference.php';
    return true;
}

$pageFile = __DIR__ . '/' . $cleanPath . '.php';

if (is_file($pageFile)) {
    require $pageFile;
    return true;
}

http_response_code(404);
require __DIR__ . '/index.php';
return true;
