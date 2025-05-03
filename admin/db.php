<?php
try{
    $db = new PDO('mysql:host=127.0.0.1;dbname=apsi;charset=utf8mb4','root','');
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
}


