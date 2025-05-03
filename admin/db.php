<?php
try{
    $db = new PDO('mysql:host=apsi;port=3306;dbname=default;charset=utf8mb4', 'root', 'root');
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
}


