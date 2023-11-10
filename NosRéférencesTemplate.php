<?php
require('admin/db.php');

$id = $_GET['id'];
function getTheRef($db, $id) {
    $sql = "SELECT * FROM reference WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch();
}

function getThePic($db, $idR) {
    $sql = "SELECT * FROM photo WHERE idR = :idR";
    $stmt= $db->prepare($sql);
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

    <main id="main">
        <section>
                <article class="grid1">
                    <!--image slider start-->
                    <div class="slider">
                        <div class="slides">
                            <!--radio buttons start-->
                            <?php foreach($picTab as $pic) { ?>
                                <input type="radio" name="radio-btn" id="radio<?= $pic["id"]; ?>">
                            <?php    } ?>
                            <!--radio buttons end-->
                            <!--slide images start-->
                            <?php foreach($picTab as $pic) { ?>
                                <div class="slide">
                                    <img src="pic/<?= $pic["titre"]; ?>" alt="">
                                </div>
                            <?php    } ?>
                            <!--slide images end-->
                        </div>
                        <!--manual navigation start-->
                        <div class="navigation-manual">
                            <?php foreach($picTab as $pic) { ?>
                                <label for="radio<?= $pic["id"]; ?>" class="manual-btn"></label>
                            <?php    } ?>
                        </div>
                        <!--manual navigation end-->
                    </div>
                    <!--image slider end-->
                </article>
                <article class="grid2">
                    <div class="desc-ref-template">
                        <?php if ($ref["titre"]): ?>
                            <p><?= $ref['titre'] ?></p> <br>
                        <?php endif; ?>

                        <?php if ($ref["commune"]): ?>
                            <p><?= $ref['commune'] ?></p> <br>
                        <?php endif; ?>

                        <?php if ($ref["domaine"]): ?>
                            <p><?= $ref['domaine'] ?></p> <br>
                        <?php endif; ?>

                        <?php if ($ref["statut"]): ?>
                            <p><?= $ref['statut'] ?></p> <br>
                        <?php endif; ?>

                        <?php if ($ref["anneeD"]): ?>
                        <p><?= $ref['anneeD'] ?>
                            <?php endif; ?>

                            <?php if ($ref["anneeD"]): ?>
                                - <?= $ref['anneeD'] ?> <br>
                            <?php else: ?>
                        </p><br>
                    <?php endif; ?>

                        <div class="desc-template">
                            <?php if ($ref["description"]): ?>
                                <p><?= $ref['description'] ?></p> <br>
                            <?php endif; ?>
                        </div>

                        <?php if ($ref["moa"]): ?>
                            <p><?= $ref['moa'] ?></p> <br>
                        <?php endif; ?>

                        <?php if ($ref["archi"]): ?>
                            <p><?= $ref['archi'] ?></p> <br>
                        <?php endif; ?>

                        <?php if ($ref["eMoe"]): ?>
                            <p><?= $ref['eMoe'] ?></p> <br>
                        <?php endif; ?>

                        <?php if ($ref["nbPhase"]): ?>
                            <p><?= $ref['nbPhase'] ?></p> <br>
                        <?php endif; ?>

                        <?php if ($ref["montant"]): ?>
                            <p><?= $ref['montant'] ?></p> <br>
                        <?php endif; ?>

                        <?php if ($ref["nbE"]): ?>
                            <p><?= $ref['nbE'] ?></p> <br>
                        <?php endif; ?>
                    </div>
                </article>


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
