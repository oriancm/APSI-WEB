<?php
    session_start();
    if ($_SESSION["session"] != "valide"){
        header("Location:login.php");
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
    <h1>Espace administrateur</h1>
    <ul>
        <li><a href="./ajouterRef.php">Ajouter une référence</a></li>
        <li><a href="">Modifier/Supprimer une référence</a></li>
    </ul>
    <br>
    <a href="./logout.php">Se déconnecter</a>
</main>


</body>
</html>
