<?php
declare(strict_types=1);

// Shared boundary for every back-office endpoint. No public page uses this session.
ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');
session_name('APSI_ADMIN');
session_set_cookie_params([
    'lifetime' => 0, 'path' => '/admin',
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'httponly' => true, 'samesite' => 'Strict',
]);
session_start();
header('Cache-Control: no-store, private');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: same-origin');
header('X-Robots-Tag: noindex, nofollow');
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; font-src 'self'; img-src 'self' blob: data:; connect-src 'self' https://geo.api.gouv.fr; object-src 'none'; base-uri 'self'; frame-ancestors 'none'; form-action 'self'");

function h($value): string {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function redirect(string $path): never {
    header('Location: ' . $path, true, 303);
    exit;
}
function csrf(): string {
    return $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
}
function csrfField(): string {
    return '<input type="hidden" name="csrf" value="' . h(csrf()) . '">';
}
function verifyCsrf(): void {
    if (!is_string($_POST['csrf'] ?? null) || !hash_equals(csrf(), $_POST['csrf'])) {
        http_response_code(403);
        exit('La demande a expiré. Rechargez la page puis réessayez.');
    }
}
function postOnly(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Allow: POST');
        http_response_code(405);
        exit('Cette action nécessite un formulaire.');
    }
    verifyCsrf();
}
function database(): PDO {
    static $connection;
    if (!$connection) {
        require __DIR__ . '/db.php';
        if (!($db instanceof PDO)) throw new RuntimeException('Base de production requise.');
        $connection = $db;
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        if ($connection->getAttribute(PDO::ATTR_DRIVER_NAME) === 'mysql') {
            $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
    }
    return $connection;
}
function expiredSession(bool $json = false): never {
    $_SESSION = [];
    if ($json) {
        http_response_code(401);
        header('Content-Type: application/json; charset=utf-8');
        exit('{"authenticated":false}');
    }
    redirect('/admin/login.php');
}
function requireAdmin(bool $json = false): array {
    $now = time();
    if (empty($_SESSION['admin_login']) || $now - ($_SESSION['last_activity'] ?? 0) > 1800
        || $now - ($_SESSION['authenticated_at'] ?? 0) > 28800) {
        expiredSession($json);
    }
    $q = database()->prepare('SELECT login, pass FROM admin WHERE login = ?');
    $q->execute([$_SESSION['admin_login']]);
    $admin = $q->fetch();
    if (!$admin || !hash_equals($_SESSION['auth_version'] ?? '', hash('sha256', $admin['pass']))) {
        expiredSession($json);
    }
    $_SESSION['last_activity'] = $now;
    if ($now - ($_SESSION['rotated_at'] ?? 0) > 900) {
        session_regenerate_id(true);
        $_SESSION['rotated_at'] = $now;
    }
    return $admin;
}
function flash(string $message): void { $_SESSION['notice'] = $message; }
function notice(): string {
    $message = $_SESSION['notice'] ?? '';
    unset($_SESSION['notice']);
    return $message;
}
function rateLimit(bool $failed = false, bool $clear = false): bool {
    $dir = sys_get_temp_dir() . '/apsi-auth-' . substr(hash('sha256', __DIR__), 0, 16);
    if (!is_dir($dir) && !@mkdir($dir, 0700, true) && !is_dir($dir)) throw new RuntimeException('Protection indisponible.');
    $key = hash('sha256', $_SERVER['REMOTE_ADDR'] ?? 'local');
    $file = fopen($dir . '/' . $key, 'c+');
    if (!$file || !flock($file, LOCK_EX)) throw new RuntimeException('Protection indisponible.');
    chmod($dir . '/' . $key, 0600);
    $state = json_decode(stream_get_contents($file), true) ?: ['start' => time(), 'count' => 0];
    if (time() - $state['start'] >= 900 || $clear) $state = ['start' => time(), 'count' => 0];
    $blocked = $state['count'] >= 8;
    if ($failed) $state['count']++;
    ftruncate($file, 0); rewind($file); fwrite($file, json_encode($state));
    flock($file, LOCK_UN); fclose($file);
    return $blocked;
}
function logAdminError(Throwable $error): void {
    error_log('APSI admin: ' . get_class($error) . ' code=' . $error->getCode());
}
function auditEvent(string $action, ?int $reference = null): void {
    error_log('APSI audit: ' . json_encode(['action' => $action, 'reference' => $reference,
        'account' => $_SESSION['admin_login'] ?? null, 'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
        'time' => gmdate('c')], JSON_UNESCAPED_UNICODE));
}
