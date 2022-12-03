<?php
//phpinfo();
require_once __DIR__ . '/../vendor/autoload.php';

session_start();

if (!empty($_POST['login']) && !empty($_POST['login']))
{
    $login = $_POST['login'];
    $pass = $_POST['pass'];

    $admin = new \Admin\Model\Admin();
    $res = $admin->findBy(["login" => $login]);

//    $sql = "SELECT * FROM admin WHERE login = :login";
//    $stmt= $db->prepare($sql);
//    $stmt->bindValue('login',$login);
//    $stmt->execute();
//    $res= $stmt->fetch(PDO::FETCH_ASSOC);

    if ($res) {
        $passwordHash = $res['pass'];
        if (password_verify($pass, $passwordHash)) {
            echo "Connexion réussie !";
            $_SESSION["session"]="valide";
            header("Location:admin.php");
        } else {
            echo "Identifiants invalides";
            $_SESSION["session"]="invalide";
        }
    } else {
        echo "Identifiants invalides";
    }
}

?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        body{
            text-align: center;
        }
    </style>
</head>
<body>

<main>
    <h1>Login Form</h1>
    <form method="POST" action="login.php">
        <div>
            <label for="">Identifiant</label>
            <input type="text" name="login" required>
        </div>
        <div>
            <label for="">Mot de passe</label>
            <input type="text" name="pass" required>
        </div>
        <div>
            <button type="submit" value="">Connexion</button>
        </div>

    </form>
</main>
</body>
</html>



