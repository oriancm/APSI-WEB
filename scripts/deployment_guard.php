<?php
// Invoked over SSH only; no HTTP endpoint and no database writes.
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}
ini_set('display_errors', '0');
try {
    $root = $argv[1] ?? '';
    require $root . '/admin/db.php';
    if (!($db instanceof PDO) || envValue('APSI_ALLOW_DEMO_DATA', '0') !== '0') {
        throw new RuntimeException('Production database required.');
    }
    $db->beginTransaction();
    $tables = $db->query('SHOW FULL TABLES WHERE Table_type = "BASE TABLE"')->fetchAll(PDO::FETCH_COLUMN);
    sort($tables);
    $state = [];
    $backup = [];
    foreach ($tables as $table) {
        $quoted = '`' . str_replace('`', '``', $table) . '`';
        $rows = $db->query('SELECT * FROM ' . $quoted)->fetchAll(PDO::FETCH_ASSOC);
        $schema = $db->query('SHOW CREATE TABLE ' . $quoted)->fetch(PDO::FETCH_NUM)[1];
        $backup[$table] = ['schema' => $schema, 'rows' => $rows];
        $hashes = array_map(fn($row) => hash('sha256', serialize($row)), $rows);
        sort($hashes);
        $state[$table] = ['count' => count($rows), 'hash' => hash('sha256', serialize($hashes)), 'schema' => hash('sha256', $schema)];
    }
    $photos = $db->query('SELECT titre FROM photo')->fetchAll(PDO::FETCH_COLUMN);
    foreach ($photos as $photo) {
        $stem = pathinfo(basename($photo), PATHINFO_FILENAME);
        if (!is_file($root . '/pic/' . $photo) && !is_file($root . '/pic/' . $stem . '.webp')) {
            throw new RuntimeException('A reference photo is missing.');
        }
    }
    $db->commit();
    if (isset($argv[2])) {
        $file = fopen($argv[2], 'x');
        if (!$file) throw new RuntimeException('Cannot create backup.');
        chmod($argv[2], 0600);
        $data = serialize($backup);
        if (fwrite($file, $data) !== strlen($data)) throw new RuntimeException('Incomplete backup.');
        fclose($file);
    }
    $settings = [];
    foreach (['.env', 'admin/.env'] as $name) {
        $settings[$name] = is_file($root . '/' . $name) ? hash_file('sha256', $root . '/' . $name) : null;
    }
    $images = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root . '/pic', FilesystemIterator::SKIP_DOTS));
    foreach ($iterator as $file) {
        if (!$file->isFile()) continue;
        $name = substr($file->getPathname(), strlen($root . '/pic/'));
        $images[$name] = hash_file('sha256', $file->getPathname());
    }
    ksort($images);
    $identity = hash('sha256', serialize([
        envValue('DB_HOST'), envValue('DB_PORT', '3306'), envValue('DB_NAME'), envValue('DB_USER'),
        $db->query('SELECT DATABASE()')->fetchColumn(),
    ]));
    echo json_encode(['database' => $identity, 'tables' => $state, 'settings' => $settings, 'images' => $images], JSON_THROW_ON_ERROR), PHP_EOL;
} catch (Throwable $error) {
    // Do not disclose server credentials or database content in deployment logs.
    fwrite(STDERR, "Production checks failed: database unavailable, demo enabled, missing photo or backup failure.\n");
    exit(1);
}
