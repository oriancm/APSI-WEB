<?php
//phpinfo();
require('./db.php');

if (!empty($_POST['user']) && !empty($_POST['pass']))
{
    $user =$_POST['user'];
    $pass =$_POST['pass'];

    var_dump($user,$pass);

}

?>