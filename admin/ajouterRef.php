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
    <?= "Référence n°". (dernierChantier($db)+1). " en cours de création"?>

    <a href="./admin.php"><h1>Espace administrateur</h1></a>
    <h2>Ajout d'une référence</h2>

    <form method="POST" action="ajouterRef.php" enctype="multipart/form-data">

        <div>
            <label for="">Titre</label>
            <input type="text" name="titre" id="" required>
        </div>

        <div>
            <label for="">Commune</label>
            <input data-search-code id="code" placeholder="Code postal">
            <input data-search-city id="ville" placeholder="Ville">
        </div>

        <ul id="result-code-or-city" style="display: none">
        </ul>
        <input type="hidden" id="villeChoisieHidden" name="villeChoisieHidden">

        <div>
            <label for="">Photos</label>
            <input type="file" name="pic[]" multiple>
        </div>

        <div>
            <label for="">Description</label>
            <textarea name="description" id="" cols="30" rows="1"></textarea>
        </div>

        <div>
            <label for="">Domaine d'activités</label>
            <select name="domaine" id="" required>
                <option value="">Choisir le domaine</option>
                <option value="1">Residence Universitaire</option>
                <option value="2">Equipements sportifs</option>
                <option value="3">Equipements culturels</option>
                <option value="4">Groupes scolaires et collèges</option>
                <option value="5">Aménagements urbains</option>
                <option value="6">Bâtiments publics</option>
                <option value="7">Crèches</option>
                <option value="8">Centre d'Incendie et de Secours</option>
                <option value="9">Hôpitaux publics</option>
                <option value="10">Logements sociaux</option>
                <option value="10">Monuments Historiques et batiments à caractère patrimonial</option>
                <option value="11">Restructuration et réhabilitation en site occupé (phasage)</option>
            </select>
        </div>

        <div>
            <label for="">Année de Début</label>
            <input type="year" name="anneeD">
            <label for="">Année de Fin</label>
            <input type="year" name="anneeF">
        </div>
        
        <div>
            <label for="">Statut</label>

            <input type="radio" name="statut" value="1">
            <label style="color: black">Opération livrée</label>

            <input type="radio" name="statut" value="2">
            <label style="color: black"for="">Travaux en cours</label>

            <input type="radio" name="statut" value="3">
            <label style="color: black"for="">Conception en cours</label>
        </div>

        <div>
            <label for="">Maitre d'ouvrage</label>
            <input type="text" name="moa">
        </div>

        <div>
            <label for="">Architecte</label>
            <input type="text" name="archi">
        </div>

        <div>
            <label for="">Equipe de maîtrise d'oeuvre</label>
            <input type="text" name="eMoe">
        </div>

        <div>
            <label for="">Nombre de phases</label>
            <input type="number" name="nbPhase">
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

<script src="../script/search-cities.js"></script>
</body>
</html>
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
    <?= "Référence n°". (dernierChantier($db)+1). " en cours de création"?>

    <a href="./admin.php"><h1>Espace administrateur</h1></a>
    <h2>Ajout d'une référence</h2>

    <form method="POST" action="ajouterRef.php" enctype="multipart/form-data">

        <div>
            <label for="">Titre</label>
            <input type="text" name="titre" id="" required>
        </div>

        <div>
            <label for="">Commune</label>
            <input data-search-code id="code" placeholder="Code postal">
            <input data-search-city id="ville" placeholder="Ville">
        </div>

        <ul id="result-code-or-city" style="display: none">
        </ul>
        <input type="hidden" id="villeChoisieHidden" name="villeChoisieHidden">

        <div>
            <label for="">Photos</label>
            <input type="file" name="pic[]" multiple>
        </div>

        <div>
            <label for="">Description</label>
            <textarea name="description" id="" cols="30" rows="1"></textarea>
        </div>

        <div>
            <label for="">Domaine d'activités</label>
            <select name="domaine" id="" required>
                <option value="">Choisir le domaine</option>
                <option value="1">Residence Universitaire</option>
                <option value="2">Equipements sportifs</option>
                <option value="3">Equipements culturels</option>
                <option value="4">Groupes scolaires et collèges</option>
                <option value="5">Aménagements urbains</option>
                <option value="6">Bâtiments publics</option>
                <option value="7">Crèches</option>
                <option value="8">Centre d'Incendie et de Secours</option>
                <option value="9">Hôpitaux publics</option>
                <option value="10">Logements sociaux</option>
                <option value="10">Monuments Historiques et batiments à caractère patrimonial</option>
                <option value="11">Restructuration et réhabilitation en site occupé (phasage)</option>
            </select>
        </div>

        <div>
            <label for="">Année de Début</label>
            <input type="year" name="anneeD">
            <label for="">Année de Fin</label>
            <input type="year" name="anneeF">
        </div>
        
        <div>
            <label for="">Statut</label>

            <input type="radio" name="statut" value="1">
            <label style="color: black">Opération livrée</label>

            <input type="radio" name="statut" value="2">
            <label style="color: black"for="">Travaux en cours</label>

            <input type="radio" name="statut" value="3">
            <label style="color: black"for="">Conception en cours</label>
        </div>

        <div>
            <label for="">Maitre d'ouvrage</label>
            <input type="text" name="moa">
        </div>

        <div>
            <label for="">Architecte</label>
            <input type="text" name="archi">
        </div>

        <div>
            <label for="">Equipe de maîtrise d'oeuvre</label>
            <input type="text" name="eMoe">
        </div>

        <div>
            <label for="">Nombre de phases</label>
            <input type="number" name="nbPhase">
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

<script src="../script/search-cities.js"></script>
</body>
</html>
