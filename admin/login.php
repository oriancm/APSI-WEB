<?php
//phpinfo();
require('./db.php');

if (!empty($_POST['user']) && !empty($_POST['pass']))
{
    $user =$_POST['user'];
    $pass =$_POST['pass'];

    $sql = "SELECT * FROM apsi";
    $stmt= $pdo->prepare($sql);
    $stmt->execute();
    $res= $stmt->fetchAll();

    var_dump($user,$pass,$res);

}

?>