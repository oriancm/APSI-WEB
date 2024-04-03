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

$ref = getTheRef($db, $id);
$picTab = getThePic($db, $id);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link rel="stylesheet" href="css/styleNosRéférencesTemplate.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:bold">
</head>
<body>

<?php include "./nav.php"; ?>

<main id="main" class="scrolled" style="margin-top: 114px;">
    <section class="mainSection">
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
                            <img src="pic/<?= $pic["titre"]; ?>" alt="">
                        </div>
                    <?php } ?>
                    <!--slide images end-->
                </div>
                <!--manual navigation start-->
                <div class="navigation-manual">
                    <?php foreach ($picTab as $pic) { ?>
                        <label for="radio<?= $pic["id"]; ?>" class="manual-btn"></label>
                    <?php } ?>
                </div>
                <!--manual navigation end-->
            </div>
            <!--image slider end-->
        </article>
        <article class="grid2">
            <div class="desc-ref-template">
                <?php if ($ref["titre"]): ?>
                    <h2><?= $ref['titre'] ?></h2> <br>
                <?php endif; ?>

                <?php if ($ref["domaine"]): ?>
                    <p class="domaine">(<?= getDomaineText($ref['domaine']); ?>)</p><br>
                <?php endif; ?>

                <?php if ($ref["commune"]): ?>
                    <p class="align-items">
                        <svg fill="#000000" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                             xmlns:xlink="http://www.w3.org/1999/xlink"
                             width="30px" height="30px" viewBox="0 0 395.71 395.71"
                             xml:space="preserve">
                        <g>
                            <path d="M197.849,0C122.131,0,60.531,61.609,60.531,137.329c0,72.887,124.591,243.177,129.896,250.388l4.951,6.738
                                c0.579,0.792,1.501,1.255,2.471,1.255c0.985,0,1.901-0.463,2.486-1.255l4.948-6.738c5.308-7.211,129.896-177.501,129.896-250.388
                                C335.179,61.609,273.569,0,197.849,0z M197.849,88.138c27.13,0,49.191,22.062,49.191,49.191c0,27.115-22.062,49.191-49.191,49.191
                                c-27.114,0-49.191-22.076-49.191-49.191C148.658,110.2,170.734,88.138,197.849,88.138z"/>
                        </g>
                        </svg>
                        <?= $ref['commune'] ?>
                    </p> <br>
                <?php endif; ?>

                <?php if ($ref["statut"]): ?>
                    <p class="align-items"><?= getStatutText($ref['statut']); ?></p><br>
                <?php endif; ?>

                <?php if ($ref["anneeD"] && $ref["anneeF"]): ?>
                    <p class="align-items">
                        <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 6V12" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16.24 16.24L12 12" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <?= $ref["anneeD"]; ?> - <?= $ref["anneeF"]; ?></p><br>
                <?php elseif ($ref["anneeD"]): ?>
                    <p class="align-items">
                        <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 6V12" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16.24 16.24L12 12" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <?= $ref["anneeD"]; ?></p><br>
                <?php endif; ?>
            </div>
        </article>
    </section>

    <section class="secondSection">
        <div class="desc-template">
            <?php if ($ref["description"]): ?>
                <p><?= $ref['description'] ?></p> <br>
            <?php endif; ?>
        </div>

        <div class="othersInfo">
            <?php if ($ref["moa"]): ?>
                <div ><p style="color: #888888">Maître d'ouvrage : </p><p> <?= $ref['moa'] ?></p> </div><br>
            <?php endif; ?>

            <?php if ($ref["archi"]): ?>
                <div ><p style="color: #888888">Architecte : </p><p><?= $ref['archi'] ?></p> </div><br>
            <?php endif; ?>

            <?php if ($ref["eMoe"]): ?>
                <div ><p style="color: #888888">Equipe de Maîtrise d'oeuvre : </p><p><?= $ref['eMoe'] ?></p> </div><br>
            <?php endif; ?>

            <?php if ($ref["montant"]): ?>
                <div ><p style="color: #888888">Montant des Travaux: </p><p><?= $ref['montant'] ?>€</p></div><br>
            <?php endif; ?>

            <?php if ($ref["nbPhase"]): ?>
                <div ><p style="color: #888888">Nombre de phases : </p><p><?= $ref['nbPhase'] ?></p> </div><br>
            <?php endif; ?>

            <?php if ($ref["nbE"]): ?>
                <div ><p style="color: #888888">Nombre d'entreprises : </p><p><?= $ref['nbE'] ?></p></div><br>
            <?php endif; ?>
        </div>

    </section>


</main>
</body>
<link rel="stylesheet" href="css/styleGlobalNotIndex.css">
</html>

<script>
    const radios = document.querySelectorAll('input[type="radio"]');
    const labels = document.querySelectorAll('.navigation-manual label');
    let checked = document.querySelector('.manual-btn');
    const firstSlide = document.querySelector('.slide');

    checked.classList.add('checked');

    // Ajoute un écouteur d'événement à chaque radio
    radios.forEach((radio, index) => {
        radio.addEventListener('change', () => {
            // Vérifie si le radio actuellement sélectionné est celui qui a été changé
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
