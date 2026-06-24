<?php
$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$hasTrailingSlash = $requestPath !== '/' && substr($requestPath, -1) === '/';
$path = '/' . trim(rawurldecode($requestPath), '/');
$path = $path === '/' ? '/' : rtrim($path, '/');

$localFile = __DIR__ . str_replace('/', DIRECTORY_SEPARATOR, $path);

if ($path !== '/' && is_file($localFile)) {
    return false;
}

if ($path === '/') {
    require __DIR__ . '/index.php';
    return true;
}

$cleanPath = trim($path, '/');

if ($cleanPath === 'admin' && !$hasTrailingSlash) {
    header('Location: /admin/', true, 302);
    return true;
}

if ($cleanPath === 'admin') {
    require __DIR__ . '/admin/index.php';
    return true;
}

if (strpos($cleanPath, 'admin/') === 0) {
    require __DIR__ . '/admin/admin.php';
    return true;
}

if (preg_match('#^reference/([0-9]+)$#', $cleanPath, $matches)) {
    $_GET['id'] = $matches[1];
    require __DIR__ . '/reference.php';
    return true;
}

if (preg_match('#^[a-zA-Z0-9_-]+$#', $cleanPath)) {
    $pageFile = __DIR__ . DIRECTORY_SEPARATOR . $cleanPath . '.php';

    if (is_file($pageFile)) {
        require $pageFile;
        return true;
    }
}

http_response_code(404);
require __DIR__ . '/index.php';
return true;
