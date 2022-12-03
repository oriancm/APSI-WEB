<?php
require('./db.php');
session_start();

if ($_SESSION["session"] != "valide"){
    header("Location:login.php");
}

if (isset($_POST['submit'])){
    $titre = htmlspecialchars_decode($_POST['titre']);

    $pic = htmlspecialchars_decode($_FILES['pic']['name']);
    $localPath = (dirname(__DIR__));
    $dirPic = $localPath.'/pic/'.$pic;
    $extPic = pathinfo($dirPic, PATHINFO_EXTENSION);
    $extValid = ['jpg','png'];

    $titre = ($_POST['titre']);
    $secteur = empty($_POST['secteur']) ? null : ($_POST['secteur']);
    $dateD = empty($_POST['dateD']) ? null : ($_POST['dateD'])."-01";
    $dateF = empty($_POST['dateF']) ? null : ($_POST['dateF'])."-01";
    $moe = ($_POST['moe']);
    $archi = ($_POST['archi']);
    $montant = empty($_POST['montant']) ? null : ($_POST['montant']);
    $nbE = empty($_POST['nbE']) ? null : ($_POST['nbE']);

//    var_dump($titre,$_FILES['pic'],$secteur,$dateD,$dateF,$moe,$archi,$montant,$nbE);

    $reference = new Reference();
    $reference = $reference // reference devient un Model
        ->setTitre($titre)
        ->setSecteur($secteur)
        ->setDateD($dateD)
        ->setDateF($dateF)
        ->setMoe($moe)
        ->setArchi($archi)
        ->setMontant($montant)
        ->setNbE($nbE);

    $reference->create($reference);

    $sql = "INSERT INTO reference (titre, secteur, dateD, dateF, moe, archi, montant, nbE)";
    $sql .= " VALUES (?,?,?,?,?,?,?,?)";
    $stmt= $db->prepare($sql);
    if ($stmt->execute([$titre,$secteur,$dateD,$dateF,$moe,$archi,$montant,$nbE])){
        echo 'reference enregistrée';
    }

    if ($pic){
        $sql = "INSERT INTO photo (titre, dir, id)";
        $sql .= " VALUES (?,?,LAST_INSERT_ID())";
        $stmt= $db->prepare($sql);
        $stmt->execute([$pic, $extPic]);
    }

    if (in_array(strtolower($extPic), $extValid)){
        move_uploaded_file($_FILES['pic']['tmp_name'], $dirPic);
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
        form > *{
            padding: 10px;
        }
    </style>
</head>
<body>

<main>
    <a href="./admin.php"><h1>Espace administrateur</h1></a>
    <h2>Ajout d'une référence</h2>

    <form method="POST" action="ajouterRef.php" enctype="multipart/form-data">
        <div>
            <label for="">Titre</label>
            <input type="text" name="titre" id="">
        </div>
        <div>
            <label for="">Photos</label>
            <input type="file" name="pic" multiple>
        </div>
        <div>
            <label for="">Secteur</label>
            <select name="secteur" id="">
                <option value="" >Choisir le secteur</option>
                <option value="1">Tertiaire</option>
                <option value="2">Milieu Hospitalier</option>
                <option value="3">EHPAD</option>
                <option value="4">Batiment Public</option>
                <option value="5">Equipement Sportif</option>
                <option value="6">Autre</option>
            </select>

        </div>
        <div>
            <label for="">Année de Début</label>
            <input type="month" name="dateD">
            <label for="">Année de Fin</label>
            <input type="month" name="dateF">
        </div>
        <div>
            <label for="">Maitre d'oeuvre</label>
            <input type="text" name="moe" id="">
        </div>
        <div>
            <label for="">Architecte</label>
            <input type="text" name="archi" id="">
        </div>
        <div>
            <label for="">Montant des travaux</label>
            <input type="number" name="montant" step="0.01">
        </div>
        <div>
            <label for="">Nombre d'entreprises</label>
            <input type="number" name="nbE">
        </div>
        <div>
            <button type="submit" name="submit" value="">Valider</button>
        </div>

    </form>
</main>


</body>
</html>

