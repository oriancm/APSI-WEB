<?php
try {
    $db = new PDO(
        'mysql:host=apsi;port=3306;dbname=default;charset=utf8mb4',
        'root',
        'root',
        [
            PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/coolify-ca.crt',
            // PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => true,
        ]
    );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully!";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage() . " | Code: " . $e->getCode() . " | Trace: " . $e->getTraceAsString());
}

