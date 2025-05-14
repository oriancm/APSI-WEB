<?php
require('./db.php');
require('./../functions/form.php');
session_start();

if ($_SESSION["session"] != "valide") {
    header("Location:login.php");
}

if (isset($_POST['submit'])) {
    // Validations
    if (empty($_POST['titre'])) {
        $errorsValidation['titre'] = 'Error Titre Vide';
    }

    if (empty($errorsValidation)) {
            $titre = ($_POST['titre']);
            $commune = empty($_POST['villeChoisieHidden']) ? null : ($_POST['villeChoisieHidden']);
            $pics = normalizeFiles();
            $description = empty($_POST['description']) ? null : ($_POST['description']);
            $domaine = ($_POST['domaine']);
            $anneeD = empty($_POST['anneeD']) ? null : ($_POST['anneeD']);
            $anneeF = empty($_POST['anneeF']) ? null : ($_POST['anneeF']);
            $moa = empty($_POST['moa']) ? null : ($_POST['moa']);
            $statut = empty($_POST['statut']) ? null : ($_POST['statut']);
            $archi = empty($_POST['archi']) ? null : ($_POST['archi']);
            $eMoe = empty($_POST['eMoe']) ? null : ($_POST['eMoe']);
            $nbPhase = empty($_POST['nbPhase']) ? null : ($_POST['nbPhase']);
            $montant = empty($_POST['montant']) ? null : ($_POST['montant']);
            $nbE = empty($_POST['nbE']) ? null : ($_POST['nbE']);

            $sql = "INSERT INTO reference (titre, commune, domaine, description, anneeD, anneeF, statut, moa, archi, eMoe, nbPhase, montant, nbE)";
            $sql .= " VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $db->prepare($sql);
            if ($stmt->execute([$titre, $commune, $domaine, $description, $anneeD, $anneeF, $statut, $moa, $archi, $eMoe, $nbPhase, $montant, $nbE])) {
                print "<p> SUCCES Informations du chantier ".$db->lastInsertId(). " enregistrées en BDD avec succès</p>";
            } else {
                print "<p> ERREUR Les informations du chantier ".$db->lastInsertId(). " n'ont pas pu être enregistrées en BDD</p>";
            }

            if ($pics[0]['name'] != '') {
                $idRef = $db->lastInsertId();
                $orderPic = 1;
                foreach($pics as $pic) {
                    $localPath = (dirname(__DIR__));
                    $dirPic = $localPath . '/pic/' . $pic['name'];
                    $extPic = pathinfo($dirPic, PATHINFO_EXTENSION);
                    $extValid = ['jpg', 'png'];
                    $sql = "INSERT INTO photo (titre, dir, idR, orderPic)";
                    $sql .= " VALUES (?,?,?,?)";
                    $stmt = $db->prepare($sql);
                    if (in_array(strtolower($extPic), $extValid) && $stmt->execute([$pic['name'], $dirPic, $idRef, $orderPic]) && move_uploaded_file($pic['tmp_name'], $dirPic)) {
                        print "<p> SUCCES Image ". $pic['name']. " enregistrée en BDD et transférée sur le serveur </p>";
                    }
                    else if (!in_array(strtolower($extPic), $extValid)) {
                        print "<p> ERREUR L'extension de l'image ". $pic['name']. " doit être un jpg ou un png</p>";
                    }
                    else if (!$stmt->execute([$pic['name'], $extPic, $idRef])) {
                        print "<p> ERREUR L'image ". $pic['name']. " n'a pas pu être enregistrée en BDD</p>";
                    }
                    else if (!move_uploaded_file($pic['tmp_name'], $dirPic)) {
                        print "<p> ERREUR L'image ". $pic['name']. " n'a pas pu être transférée sur le serveur</p>";
                    }
                    $orderPic++;
                }
            }
    }
}

function dernierChantier($db) {
    $sql = "SELECT MAX(id) FROM reference";
    $stmt= $db->prepare($sql);
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
return $res['MAX(id)'];
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
    <link rel="stylesheet" href="/css/styleAjouterRef.css">
</head>
<body>

<main>
    <h1><a href="./admin.php">Espace administrateur</a></h1>
    <h2>Ajout d'une référence (Référence n°<?= (dernierChantier($db) + 1) ?>)</h2>

    <form method="POST" action="ajouterRef.php" enctype="multipart/form-data">
        <div>
            <label for="titre">Titre</label>
            <input type="text" name="titre" id="titre" required>
        </div>

        <div>
            <label for="code">Commune</label>
            <input data-search-code id="code" placeholder="Code postal">
            <input data-search-city id="ville" placeholder="Ville">
            <input type="hidden" id="villeChoisieHidden" name="villeChoisieHidden">
        </div>
        <ul id="result-code-or-city" style="display: none"></ul>

        <div>
            <label for="pic">Photos</label>
            <input type="file" name="pic[]" id="pic" multiple>
        </div>

        <div>
            <label for="description">Description</label>
            <textarea name="description" id="description" cols="30" rows="5"></textarea>
        </div>

        <div>
            <label for="domaine">Domaine d'activités</label>
            <select name="domaine" id="domaine" required>
                <option value="">Choisir le domaine</option>
                <option value="1">Résidence Universitaire</option>
                <option value="2">Équipements sportifs</option>
                <option value="3">Équipements culturels</option>
                <option value="4">Groupes scolaires et collèges</option>
                <option value="5">Aménagements urbains</option>
                <option value="6">Bâtiments publics</option>
                <option value="7">Crèches</option>
                <option value="8">Centre d'Incendie et de Secours</option>
                <option value="9">Hôpitaux publics</option>
                <option value="10">Logements sociaux</option>
                <option value="11">Monuments Historiques et bâtiments à caractère patrimonial</option>
                <option value="12">Restructuration et réhabilitation en site occupé (phasage)</option>
            </select>
        </div>

        <div>
            <label for="">Année de Début</label>
            <input type="year" name="anneeD">
            <label for="">Année de Fin</label>
            <input type="year" name="anneeF">
            <label for="duree_travaux_mois">Durée des travaux (mois)</label>
            <input type="number" name="duree_travaux_mois" id="duree_travaux_mois" min="0">
        </div>
        

        <div>
            <label for="statut">Statut</label>
            <input type="radio" name="statut" id="statut1" value="1">
            <label for="statut1">Opération livrée</label>
            <input type="radio" name="statut" id="statut2" value="2">
            <label for="statut2">Travaux en cours</label>
            <input type="radio" name="statut" id="statut3" value="3">
            <label for="statut3">Conception en cours</label>
        </div>

        <div>
            <label for="moa">Maître d'ouvrage</label>
            <input type="text" name="moa" id="moa">
        </div>

        <div>
            <label for="montant">Montant des travaux (€)</label>
            <input type="number" name="montant" id="montant" step="0.01" min="0">
        </div>

        <div>
            <label for="nbE">Nombre d'entreprises</label>
            <input type="number" name="nbE" id="nbE" min="0">
        </div>

        <div>
            <label for="nombre_lots">Nombre de lots</label>
            <input type="number" name="nombre_lots" id="nombre_lots" min="0">
        </div>

        <div>
            <button type="submit" name="submit">Valider</button>
        </div>
    </form>
</main>

<script src="../script/search-cities.js"></script>
</body>
</html>
