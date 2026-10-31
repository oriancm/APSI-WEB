<?php
require_once __DIR__ . '/bootstrap.php';
postOnly();
auditEvent('logout');
$_SESSION = [];
setcookie(session_name(), '', ['expires' => time() - 42000, 'path' => '/admin', 'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off', 'httponly' => true, 'samesite' => 'Strict']);
session_destroy();
redirect('/admin/login.php');
