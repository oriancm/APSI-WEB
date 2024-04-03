<?php
require('admin/db.php');
require('functions/htmlPrint.php');

function getAllRef($db) {
    $sql = "SELECT * FROM reference";
    $stmt= $db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllPic($db) {
    $sql = "SELECT * FROM photo ORDER BY orderPic ASC";
    $stmt= $db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$refTab = getAllRef($db);
$picTab = getAllPic($db);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="css/styleNosRéférences.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:bold">
</head>
<body>

    <?php include "./navReference.php"; ?>

    <main id="main" class="scrolled">
            <section>
                <?php foreach($refTab as $ref): ?>
                    <article>
                        <div>
                            <?php
                                foreach($picTab as $pic) {
                                    if($pic['idR'] == $ref['id'] && $pic['orderPic'] == 1) {
                                        $picOfRef = $pic;
                                    }
                                }
                            ?>
                            <a href="NosRéférencesTemplate.php?id=<?= $ref['id'] ?>"><img class="image-ref" src="pic/<?= $picOfRef["titre"]; ?>" alt=""></a>
                        </div>
                        <div class="desc-ref">
                            <p><?= $ref["titre"]; ?></p>
                            <p class="commune"><?= $ref["commune"]; ?></p>


<!--                            --><?php //= getStatutText($ref['statut']); ?>
<!--                            --><?php //if ($ref["anneeD"] && $ref["anneeF"]): ?>
<!--                                --><?php //= $ref["anneeD"]; ?><!-- - --><?php //= $ref["anneeF"]; ?><!--<br>-->
<!--                            --><?php //elseif($ref["anneeD"]): ?>
<!--                                --><?php //= $ref["anneeD"]; ?><!--<br>-->
<!--                            --><?php //endif; ?>

                        </div>
                    </article>
                <?php endforeach ?>
            </section>
    </main>
</body>
<link rel="stylesheet" href="css/styleGlobalNotIndex.css">
</html>

<script>

    const article = document.querySelector('article');

    addEventListener('load', (event) => {
        var navElement = document.getElementById('nav');
        var navHeight = navElement.offsetHeight;
        // console.warn(navHeight);

        var mainElement = document.getElementById('main');
        mainElement.style.marginTop = navHeight + 'px';
    });

</script>