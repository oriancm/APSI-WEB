<?php
function loadEnvFile(string $envFile): void
{
    if (!file_exists($envFile)) {
        return;
    }

    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim(trim($value), "\"'");

        if (getenv($key) === false && !isset($_ENV[$key])) {
            $_ENV[$key] = $value;
            putenv($key . '=' . $value);
        }
    }
}

function envValue(string $key, ?string $default = null): ?string
{
    $value = getenv($key);
    if ($value !== false) {
        return $value;
    }

    return $_ENV[$key] ?? $default;
}

class NullDbStatement
{
    public function bindValue($param, $value, $type = null): bool
    {
        return true;
    }

    public function execute($params = null): bool
    {
        return true;
    }

    public function fetchAll($mode = null): array
    {
        return [];
    }

    public function fetch($mode = null)
    {
        return false;
    }

    public function fetchColumn($column = 0)
    {
        return false;
    }
}

class NullDb
{
    public function prepare(string $sql): NullDbStatement
    {
        return new NullDbStatement();
    }

    public function query(string $sql): NullDbStatement
    {
        return new NullDbStatement();
    }
}

loadEnvFile(__DIR__ . '/../.env');
loadEnvFile(__DIR__ . '/.env');

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
];

if (!empty(envValue('DB_SSL_CA'))) {
    if (defined('PDO::MYSQL_ATTR_SSL_CA')) {
        $options[PDO::MYSQL_ATTR_SSL_CA] = envValue('DB_SSL_CA');
    }
}

try {
    $db = new PDO(
        'mysql:host=' . envValue('DB_HOST', 'db') .
        ';port=' . envValue('DB_PORT', '3306') .
        ';dbname=' . envValue('DB_NAME', 'apsi') .
        ';charset=utf8mb4',
        envValue('DB_USER', 'apsi'),
        envValue('DB_PASSWORD', ''),
        $options
    );
} catch (Throwable $e) {
    // Demo data must be explicitly enabled for local development.
    // Production must never silently replace CRM data with hard-coded references.
    if (envValue('APSI_ALLOW_DEMO_DATA', '0') === '1') {
        $db = new NullDb();
    } else {
        error_log('APSI: production database unavailable (no demo fallback).');
        if (PHP_SAPI === 'cli') {
            throw new RuntimeException('Production database unavailable.');
        }
        http_response_code(503);
        header('Content-Type: text/plain; charset=utf-8');
        header('Retry-After: 60');
        exit('Service temporairement indisponible. Merci de réessayer plus tard.');
    }
}
