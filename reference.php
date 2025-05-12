<?php
require('admin/db.php');
require('functions/htmlPrint.php');

$id = $_GET['id'];
function getTheRef($db, $id)
{
    $sql = "SELECT * FROM reference WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch();
}

function getThePic($db, $idR)
{
    $sql = "SELECT * FROM photo WHERE idR = :idR ORDER BY orderPic ASC";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':idR', $idR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function formatNumber($number) {
    return number_format($number, 0, ',', ' ');
}

$ref = getTheRef($db, $id);
$picTab = getThePic($db, $id);
$showCarousel = count($picTab) > 1;
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>APSI BTP - Référence</title>
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/styleNosRéférencesTemplate.css">
    <link rel="stylesheet" href="css/styleGlobalNotIndex.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:bold">
</head>
<body>

<?php include "./nav.php"; ?>

<main id="main" class="scrolled" style="margin-top: 114px;">
<h1 id="references-title"><a href="./references.php">Nos Références</a></h1>
    <section class="mainSection">
        <?php if ($ref["titre"]): ?>
            <h2 class="title"><?= $ref['titre'] ?></h2>
        <?php endif; ?>
        <article class="grid1">
            <!--image slider start-->
            <div class="slider">
                <div class="slides">
                    <!--radio buttons start-->
                    <?php foreach ($picTab as $pic) { ?>
                        <input type="radio" name="radio-btn" id="radio<?= $pic["id"]; ?>">
                    <?php } ?>
                    <!--radio buttons end-->
                    <!--slide images start-->
                    <?php foreach ($picTab as $pic) { ?>
                        <div class="slide">
                            <img src="/pic/<?= $pic["titre"]; ?>" alt="">
                        </div>
                    <?php } ?>
                    <!--slide images end-->
                </div>
                <!--manual navigation start-->
                <?php if ($showCarousel): ?>
                <div class="navigation-manual">
                    <?php foreach ($picTab as $pic) { ?>
                        <label for="radio<?= $pic["id"]; ?>" class="manual-btn"></label>
                    <?php } ?>
                </div>
                <?php endif; ?>
                <!--manual navigation end-->
            </div>
            <!--image slider end-->
        </article>
        <article class="grid2">
            <div class="info-container">
                <?php if ($ref["titre"]): ?>
                    <h2><?= $ref['titre'] ?></h2>
                <?php endif; ?>
                <?php if ($ref["description"]): ?>
                    <p class="desc"><?= $ref['description'] ?></p>
                <?php endif; ?>
                <section class="justify">
                <section class="infos">
                    <?php if ($ref["commune"]): ?>
                        <div><p>Commune : </p><p><?= $ref['commune'] ?></p></div>
                    <?php endif; ?>

                    <?php if ($ref["statut"]): ?>
                        <div><p>Statut : </p><p><?= getStatutText($ref['statut']); ?></p></div>
                    <?php endif; ?>

                    <?php if ($ref["anneeD"] && $ref["anneeF"]): ?>
                        <div><p>Période : </p><p><?= $ref["anneeD"] ?> - <?= $ref["anneeF"] ?> <?= $ref["duree_travaux_mois"] ? "(" . $ref["duree_travaux_mois"] . " mois)" : "" ?></p></div>
                    <?php elseif ($ref["anneeD"]): ?>
                        <div><p>Année : </p><p><?= $ref["anneeD"] ?> <?= $ref["duree_travaux_mois"] ? "(" . $ref["duree_travaux_mois"] . " mois)" : "" ?></p></div>
                    <?php endif; ?>

                    <?php if ($ref["moa"]): ?>
                        <div><p>Maître d'ouvrage : </p><p><?= $ref['moa'] ?></p></div>
                    <?php endif; ?>

                    <?php if ($ref["archi"]): ?>
                        <div><p>Architecte : </p><p><?= $ref['archi'] ?></p></div>
                    <?php endif; ?>

                    <?php if ($ref["eMoe"]): ?>
                        <div><p>Equipe de Maîtrise d'oeuvre : </p><p><?= $ref['eMoe'] ?></p></div>
                    <?php endif; ?>

                    <?php if ($ref["montant"]): ?>
                        <div><p>Montant des Travaux : </p><p><?= formatNumber($ref['montant']) ?> €</p></div>
                    <?php endif; ?>

                    <?php if ($ref["nbPhase"]): ?>
                        <div><p>Nombre de phases : </p><p><?= $ref['nbPhase'] ?></p></div>
                    <?php endif; ?>

                    <?php if ($ref["nbE"]): ?>
                        <div><p>Nombre d'entreprises : </p><p><?= $ref['nbE'] ?></p></div>
                    <?php endif; ?>

                    <?php if ($ref["nombre_lots"]): ?>
                        <div><p>Nombre de lots : </p><p><?= $ref['nombre_lots'] ?></p></div>
                    <?php endif; ?>
                </section>
                </section>
                </div>

                
        </article>
    </section>
    <p class="slogan" style="margin-top: 10px">APSI BTP, vos projets en toute sérénité…</p>

</main>
</body>
</html>

<?php if ($showCarousel): ?>
<script>
    const radios = document.querySelectorAll('input[type="radio"]');
    const labels = document.querySelectorAll('.navigation-manual label');
    let checked = document.querySelector('.manual-btn');
    const firstSlide = document.querySelector('.slide');

    checked.classList.add('checked');

    radios.forEach((radio, index) => {
        radio.addEventListener('change', () => {
            if (radio.checked) {
                checked.classList.remove('checked');
                checked = document.querySelector(`label[for="${radio.id}"]`);
                checked.classList.add('checked');
                const marginLeftValue = index * -20;
                firstSlide.style.marginLeft = `${marginLeftValue}%`;
            }
        });
    });
</script>
<?php endif; ?>