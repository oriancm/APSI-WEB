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

function getSameDomainReferences($db, $domaine, $currentId)
{
    $sql = "SELECT * FROM reference WHERE domaine = :domaine AND id != :currentId";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':domaine', $domaine);
    $stmt->bindParam(':currentId', $currentId);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function formatNumber($number) {
    return number_format($number, 0, ',', ' ');
}

$ref = getTheRef($db, $id);
if (!$ref) {
    header('Location: /references.php');
    exit;
}
$picTab = getThePic($db, $id);
$showCarousel = count($picTab) > 1;

// Récupérer les références du même domaine
$sameDomainRefs = [];
if ($ref['domaine']) {
    $sameDomainRefs = getSameDomainReferences($db, $ref['domaine'], $id);
}

// Récupérer toutes les photos pour les références du même domaine
$allPics = [];
if (!empty($sameDomainRefs)) {
    $sql = "SELECT * FROM photo ORDER BY orderPic ASC";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $allPics = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- SEO Meta Tags -->
    <title><?= htmlspecialchars($ref['titre']) ?> - APSI BTP | Référence BTP en <?= htmlspecialchars($ref['commune']) ?></title>
    <meta name="description" content="Découvrez le projet <?= htmlspecialchars($ref['titre']) ?> réalisé par APSI BTP à <?= htmlspecialchars($ref['commune']) ?>. <?= htmlspecialchars(substr($ref['description'], 0, 150)) ?>...">
    <meta name="keywords" content="<?= htmlspecialchars($ref['titre']) ?>, <?= htmlspecialchars($ref['commune']) ?>, APSI BTP, OPC, maîtrise d'œuvre, construction, Provence, PACA">
    <meta name="author" content="APSI BTP">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="https://apsi-btp.fr/reference/<?= $ref['id'] ?>">
    <meta property="og:title" content="<?= htmlspecialchars($ref['titre']) ?> - APSI BTP">
    <meta property="og:description" content="Découvrez le projet <?= htmlspecialchars($ref['titre']) ?> réalisé par APSI BTP à <?= htmlspecialchars($ref['commune']) ?>.">
    <meta property="og:image" content="/img/APSI.png">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://apsi-btp.fr/reference/<?= $ref['id'] ?>">
    <meta property="twitter:title" content="<?= htmlspecialchars($ref['titre']) ?> - APSI BTP">
    <meta property="twitter:description" content="Découvrez le projet <?= htmlspecialchars($ref['titre']) ?> réalisé par APSI BTP à <?= htmlspecialchars($ref['commune']) ?>.">
    <meta property="twitter:image" content="/img/APSI.png">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="manifest" href="/site.webmanifest">

    <link rel="stylesheet" href="/css/reference.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:bold">
    <style>
      .hidden-until-loaded {
        opacity: 0;
        transition: opacity 0.3s;
      }
      .show-after-load {
        opacity: 1 !important;
      }
    </style>
</head>
<body>

<?php include "./nav.php"; ?>

<main id="main" class="scrolled hidden-until-loaded">
    <div class="content-wrapper">
        <!-- Titre et navigation au même niveau -->
        <div class="top-section">
            <div class="back-navigation">
                <a href="/references" class="back-link">← Nos Références</a>
            </div>
            <?php if ($ref["titre"]): ?>
                <h1 class="reference-title"><?= $ref['titre'] ?></h1>
            <?php endif; ?>
            <div class="spacer"></div> <!-- Spacer pour centrer parfaitement le titre -->
        </div>

        <section class="mainSection">
            <div class="reference-card">
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
                        <section class="justify">
                            <section class="infos">
                                <?php if ($ref["commune"]): ?>
                                    <div><p>Lieu : </p><p><?= $ref['commune'] ?></p></div>
                                <?php endif; ?>

                                <?php if ($ref["domaine"]): ?>
                                    <div><p>Domaine : </p><p><?= getDomaineText($ref['domaine']); ?></p></div>
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
                                    <div><p>Équipe de Maîtrise d'œuvre : </p><p><?= $ref['eMoe'] ?></p></div>
                                <?php endif; ?>

                                <?php if ($ref["montant"]): ?>
                                    <div><p>Montant des Travaux : </p><p><?= formatNumber($ref['montant']) ?> € HT</p></div>
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
                        <?php if ($ref["description"] && trim($ref["description"]) !== ""): ?>
                            <article class="divDesc">
                                <p class="desc"><?= $ref['description'] ?></p>
                            </article>
                        <?php endif; ?>
                    </div>
                </article>
            </div>
        </section>

        <!-- Section références du même domaine -->
        <?php if (!empty($sameDomainRefs)): ?>
            <section class="same-domain-section">
                <h2 class="same-domain-title">Références du même domaine</h2>
                <div class="same-domain-grid">
                    <?php foreach($sameDomainRefs as $sameDomainRef): ?>
                        <article class="same-domain-card">
                            <a class="card-link" href="/reference/<?= $sameDomainRef['id'] ?>"></a>
                            <div>
                                <?php
                                    $picOfRef = null;
                                    foreach($allPics as $pic) {
                                        if($pic['idR'] == $sameDomainRef['id'] && $pic['orderPic'] == 1) {
                                            $picOfRef = $pic;
                                            break;
                                        }
                                    }
                                ?>
                                <div>
                                    <?php if ($picOfRef): ?>
                                        <img class="same-domain-image" src="/pic/<?= $picOfRef["titre"]; ?>" alt="">
                                    <?php else: ?>
                                        <div class="no-image">Aucune image disponible</div>
                                    <?php endif; ?>
                                </div>
                                <div class="same-domain-desc">
                                    <div class="text-wrapper"><p><?= $sameDomainRef["titre"]; ?></p></div>
                                    <div class="text-wrapper"><p class="commune"><?= $sameDomainRef["commune"]; ?></p></div>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    </div>
    <div class="pb">
        <p class="slogan" style="margin-top: 0;">APSI BTP, vos projets en toute sérénité…</p>
    </div>
</main>

<link rel="stylesheet" href="/css/styleGlobalNotIndex.css">
<script>
window.addEventListener('load', function() {
    var navElement = document.getElementById('nav');
    var navHeight = navElement ? navElement.offsetHeight : 0;
    var mainElement = document.getElementById('main');
    if (mainElement) {
        mainElement.style.marginTop = navHeight + 'px';
        mainElement.classList.remove('hidden-until-loaded');
        mainElement.classList.add('show-after-load');
    }
    if (navElement) {
        navElement.classList.add('show-after-load');
    }
});
</script>
</body>
</html>

<?php if ($showCarousel): ?>
<script>
    const radios = document.querySelectorAll('input[type="radio"]');
    const labels = document.querySelectorAll('.navigation-manual label');
    let checked = document.querySelector('.manual-btn');
    const firstSlide = document.querySelector('.slide');
    const slider = document.querySelector('.slider');
    
    let currentSlide = 0;
    const totalSlides = radios.length;

    checked.classList.add('checked');
    
    // Fonction pour changer de slide
    function changeSlide(index) {
        if (index >= 0 && index < totalSlides) {
            checked.classList.remove('checked');
            radios[index].checked = true;
            checked = document.querySelector(`label[for="${radios[index].id}"]`);
            checked.classList.add('checked');
            const marginLeftValue = index * -20;
            firstSlide.style.marginLeft = `${marginLeftValue}%`;
            currentSlide = index;
        }
    }

    radios.forEach((radio, index) => {
        radio.addEventListener('change', () => {
            if (radio.checked) {
                changeSlide(index);
            }
        });
    });

    // Swipe tactile pour mobile
    let touchStartX = 0;
    let touchEndX = 0;
    let isTouch = false;

    slider.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
        isTouch = true;
    });

    slider.addEventListener('touchend', (e) => {
        if (!isTouch) return;
        
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
        isTouch = false;
    });

    function handleSwipe() {
        const swipeThreshold = 50; // Distance minimum pour déclencher le swipe
        const swipeDistance = touchStartX - touchEndX;

        if (Math.abs(swipeDistance) > swipeThreshold) {
            if (swipeDistance > 0) {
                // Swipe vers la gauche - slide suivante
                const nextSlide = currentSlide + 1;
                if (nextSlide < totalSlides) {
                    changeSlide(nextSlide);
                }
            } else {
                // Swipe vers la droite - slide précédente
                const prevSlide = currentSlide - 1;
                if (prevSlide >= 0) {
                    changeSlide(prevSlide);
                }
            }
        }
    }
</script>
<?php endif; ?>

