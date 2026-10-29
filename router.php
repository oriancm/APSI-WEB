<?php
$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$hasTrailingSlash = $requestPath !== '/' && substr($requestPath, -1) === '/';
$path = '/' . trim(rawurldecode($requestPath), '/');
$path = $path === '/' ? '/' : rtrim($path, '/');

$localFile = __DIR__ . str_replace('/', DIRECTORY_SEPARATOR, $path);

if ($path === '/sitemap.xml') { require __DIR__ . '/sitemap.php'; return true; }
if (preg_match('#^/(?:index(?:\.php)?|(?:aboutUs|professions|references|clients|contact|legalNotices|privacyPolicy)\.php)$#', $path) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $target = preg_match('#^/index#', $path) ? '/' : substr($path, 0, -4);
    header('Location: ' . $target . (empty($_SERVER['QUERY_STRING']) ? '' : '?' . $_SERVER['QUERY_STRING']), true, 301);
    return true;
}
if ($hasTrailingSlash && preg_match('#^/(?:aboutUs|professions|references|clients|contact|legalNotices|privacyPolicy|reference/[0-9]+)$#', $path)) {
    header('Location: ' . $path . (empty($_SERVER['QUERY_STRING']) ? '' : '?' . $_SERVER['QUERY_STRING']), true, 301);
    return true;
}
if (in_array($path, ['/reference.php', '/reference'], true) && preg_match('/^[0-9]+$/', $_GET['id'] ?? '') && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /reference/' . (int)$_GET['id'], true, 301); return true;
}
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
require __DIR__ . '/404.php';
return true;
