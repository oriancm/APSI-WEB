<?php
//phpinfo();
require('./db.php');

if (!empty($_POST['user']) && !empty($_POST['pass']))
{
    $user =$_POST['user'];
    $pass =$_POST['pass'];

    $sql = "SELECT * FROM admin WHERE login = :user";
    $stmt= $db->prepare($sql);
    $stmt->bindValue('user',$user);
    $stmt->execute();
    $res= $stmt->fetch(PDO::FETCH_ASSOC);

    if ($res) {
        $passwordHash = $res['pass'];
        if (password_verify($pass, $passwordHash)) {
            echo "Connexion réussie !";
        } else {
            echo "Identifiants invalides";
        }
    } else {
        echo "Identifiants invalides";
    }

}

?>