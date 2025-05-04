<?php
try{
    $db = new PDO('mysql:host=i4kc8k04k00wc00sswk8oswg;port=3306;dbname=default;charset=utf8mb4', 'root', 'root');
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
}


