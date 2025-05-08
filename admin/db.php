<?php
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
];

if (!empty($_ENV['DB_SSL_CA'])) {
    $options[PDO::MYSQL_ATTR_SSL_CA] = $_ENV['DB_SSL_CA'];
}

try {
    $db = new PDO(
        'mysql:host=' . $_ENV['DB_HOST'] .
        ';port=' . $_ENV['DB_PORT'] .
        ';dbname=' . $_ENV['DB_NAME'] .
        ';charset=utf8mb4',
        $_ENV['DB_USER'],
        $_ENV['DB_PASSWORD'],
        $options
    );
    // echo "Connected successfully!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
