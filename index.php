<?php
require('admin/db.php');
function getHomeReferences($db, $limit = 12) {
    $sql = "SELECT * FROM reference ORDER BY id ASC LIMIT :limit";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getFirstPicForRefs($db, $refIds) {
    if (empty($refIds)) return [];
    $in = implode(',', array_fill(0, count($refIds), '?'));
    $sql = "SELECT * FROM photo WHERE idR IN ($in) AND orderPic = 1";
    $stmt = $db->prepare($sql);
    foreach ($refIds as $k => $id) {
        $stmt->bindValue($k+1, $id, PDO::PARAM_INT);
    }
    $stmt->execute();
    $pics = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $byRef = [];
    foreach ($pics as $pic) {
        $byRef[$pic['idR']] = $pic;
    }
    return $byRef;
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>APSI BTP - Vos projets en toute sérénité | Ordonnancement Pilotage Coordination</title>
    <meta name="description" content="APSI BTP, spécialiste en Ordonnancement Pilotage et Coordination (OPC) et Maîtrise d'Œuvre d'Exécution (MOEX) depuis 2007. Plus de 100 références dans le BTP en Provence-Alpes-Côte d'Azur.">
    <meta name="keywords" content="APSI BTP, OPC, ordonnancement pilotage coordination, maîtrise d'œuvre, construction, réhabilitation, chantier, Provence, PACA, Vaucluse, Bouches-du-Rhône">
    <meta name="author" content="APSI BTP">
    <meta name="robots" content="index, follow">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://apsi-btp.fr/">
    <meta property="og:title" content="APSI BTP - Vos projets en toute sérénité">
    <meta property="og:description" content="Spécialiste en Ordonnancement Pilotage et Coordination (OPC) et Maîtrise d'Œuvre d'Exécution (MOEX) depuis 2007.">
    <meta property="og:image" content="/img/APSI.png">
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://apsi-btp.fr/">
    <meta property="twitter:title" content="APSI BTP - Vos projets en toute sérénité">
    <meta property="twitter:description" content="Spécialiste en Ordonnancement Pilotage et Coordination (OPC) et Maîtrise d'Œuvre d'Exécution (MOEX) depuis 2007.">
    <meta property="twitter:image" content="/img/APSI.png">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/references.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:bold">
    <style>
    /* ... styles du carrousel et du bouton ... */
    .carousel-container {
        width: 100%;
        max-width: 100vw;
        overflow: visible;
        margin: 0 auto;
        padding: 0;
        position: relative;
        background: none;
        margin-top: 120px;
        border: none;
        box-shadow: none;
        height: auto;
        padding-bottom: 24px;
    }
    .carousel-track {
        display: block;
        white-space: nowrap;
        will-change: transform;
        border: none;
        box-shadow: none;
        overflow: visible;
        height: auto;
    }
    .carousel-card {
        display: inline-block;
        vertical-align: top;
        width: 140px;
        background-color: var(--card-background);
        border-radius: var(--card-border-radius);
        box-shadow: var(--card-shadow);
        overflow: hidden;
        transition: var(--card-transition);
        border: var(--card-border);
        height: auto;
        cursor: pointer;
        position: relative;
        margin-right: 16px;
        z-index: 1;
    }
    .carousel-card a.card-link {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 10;
    }
    .carousel-card:hover {
        transform: translateY(-12px) scale(1.04);
        box-shadow: 0 16px 32px rgba(0,0,0,0.18), 0 2px 8px rgba(0,0,0,0.08);
        z-index: 100;
    }
    .carousel-card > div:first-child {
        width: 100%;
        overflow: visible;
    }
    .carousel-card > div > div:first-child {
        width: 100%;
        height: 90px;
        overflow: hidden;
        border-bottom: var(--image-border);
        border-top-left-radius: var(--card-border-radius);
        border-top-right-radius: var(--card-border-radius);
    }
    .same-domain-image {
        width: 100%;
        height: 90px;
        object-fit: cover;
        transition: transform 0.5s;
        display: block;
        border-top-left-radius: var(--card-border-radius);
        border-top-right-radius: var(--card-border-radius);
    }
    .carousel-card:hover .same-domain-image {
        transform: scale(1.05);
    }
    .no-image {
        width: 100%;
        height: 90px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f5f5f5;
        color: #999;
        font-size: 11px;
        font-style: italic;
        border-top-left-radius: var(--card-border-radius);
        border-top-right-radius: var(--card-border-radius);
    }
    .same-domain-desc {
        padding: 8px 6px;
        text-align: center;
        background-color: var(--card-background);
        border: none;
        box-shadow: none;
        border-bottom-left-radius: var(--card-border-radius);
        border-bottom-right-radius: var(--card-border-radius);
    }
    .same-domain-desc .text-wrapper {
        margin-bottom: 3px;
    }
    .same-domain-desc .text-wrapper:last-child {
        margin-bottom: 0;
    }
    .same-domain-desc .text-wrapper p {
        margin: 0;
        font-weight: bold;
        color: var(--title-color);
        font-size: 11px;
        white-space: normal;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
        line-height: 1.3;
        padding-bottom: 1px;
        text-align: center;
        font-family: 'Montserrat', sans-serif;
        min-height: 2.6em;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    .same-domain-desc .text-wrapper p.commune {
        font-weight: normal;
        color: var(--subtitle-color);
        font-size: 10px;
        margin-top: 2px;
        min-height: unset;
        display: block;
        -webkit-line-clamp: unset;
        -webkit-box-orient: unset;
    }
    @media (max-width: 600px) {
        .carousel-container {
            padding-bottom: 12px;
        }
        .carousel-card {
            width: 100px;
            margin-right: 8px;
        }
        .carousel-card > div > div:first-child, .same-domain-image, .no-image {
            height: 60px;
        }
        .same-domain-desc {
            padding: 5px;
        }
        .same-domain-desc .text-wrapper p {
            font-size: 8px;
            min-height: 2.1em;
        }
        .same-domain-desc .text-wrapper p.commune {
            font-size: 7px;
        }
    }
    .carousel-btn-animated {
        background: var(--accent-color);
        color: white;
        padding: 12px 32px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        font-family: 'Montserrat', sans-serif;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
        margin-top: 30px;
        display: inline-block;
        animation: pulseBtn 1.8s infinite alternate;
    }
    .carousel-btn-animated:hover {
        background: #1e6bb8;
        color: #fff;
    }
    @keyframes pulseBtn {
        0% { transform: scale(1); box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        100% { transform: scale(1.06); box-shadow: 0 8px 24px rgba(30,144,255,0.18); }
    }
    </style>
</head>
<body style="overflow: hidden; width: 100vw; height: 100vh;">
    <?php include "./nav.php"; ?>
    <main id="main" class="hidden-until-loaded" style="padding-top: 110px; height: calc(100vh - 110px); width: 100vw; overflow: hidden;">
        <aside>
            <h1>APSI BTP</h1>
        </aside>
        <section style="margin:0; padding:0;">
            <div class="carousel-container" style="margin:0; padding:0; margin-top:120px; border:none; box-shadow:none; overflow:visible; height:auto; padding-bottom:24px; max-width:100vw; width:100%;">
                <div class="carousel-track" id="carousel-track" style="overflow:visible; height:auto;">
                <?php
                $refs = getHomeReferences($db, 12);
                $refIds = array_column($refs, 'id');
                $pics = getFirstPicForRefs($db, $refIds);
                foreach($refs as $ref): ?>
                    <article class="carousel-card same-domain-card">
                        <a class="card-link" href="reference.php?id=<?= $ref['id'] ?>"></a>
                        <div>
                            <div>
                                <?php if (!empty($pics[$ref['id']])): ?>
                                    <img class="same-domain-image" src="pic/<?= htmlspecialchars($pics[$ref['id']]['titre']) ?>" alt="">
                                <?php else: ?>
                                    <div class="no-image">Aucune image disponible</div>
                                <?php endif; ?>
                            </div>
                            <div class="same-domain-desc">
                                <div class="text-wrapper"><p><?= htmlspecialchars($ref['titre']) ?></p></div>
                                <div class="text-wrapper"><p class="commune"><?= htmlspecialchars($ref['commune']) ?></p></div>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
                <?php // Dupliquer la séquence de base une seule fois pour la boucle infinie
                foreach($refs as $ref): ?>
                    <article class="carousel-card same-domain-card">
                        <a class="card-link" href="reference.php?id=<?= $ref['id'] ?>"></a>
                        <div>
                            <div>
                                <?php if (!empty($pics[$ref['id']])): ?>
                                    <img class="same-domain-image" src="pic/<?= htmlspecialchars($pics[$ref['id']]['titre']) ?>" alt="">
                                <?php else: ?>
                                    <div class="no-image">Aucune image disponible</div>
                                <?php endif; ?>
                            </div>
                            <div class="same-domain-desc">
                                <div class="text-wrapper"><p><?= htmlspecialchars($ref['titre']) ?></p></div>
                                <div class="text-wrapper"><p class="commune"><?= htmlspecialchars($ref['commune']) ?></p></div>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
                </div>
            </div>
            <div style="text-align:center;">
                <a href="references.php" class="carousel-btn-animated">Découvrir nos références</a>
            </div>
        </section>
    </main>
    <footer>
        <div class="legal-links">
            <a href="legalNotices">Mentions légales</a>
            <a href="privacyPolicy">Politique de confidentialité</a>
        </div>
    </footer>
</body>
<link rel="stylesheet" href="/css/indexResponsive.css">
<script>
// Carrousel infini fluide, duplication une seule fois pour boucle parfaite, sens droite -> gauche
window.addEventListener('DOMContentLoaded', function() {
    const track = document.getElementById('carousel-track');
    if (!track) return;
    const cards = Array.from(track.children);
    if (cards.length === 0) return;
    // Largeur de la séquence de base (12 cards)
    let blockWidth = 0;
    for (let i = 0; i < cards.length / 2; i++) {
        blockWidth += cards[i].offsetWidth;
    }
    // Animation CSS : translate de -blockWidth px à 0 (droite -> gauche)
    track.style.animation = `train-scroll ${(blockWidth/35).toFixed(1)}s linear infinite`;
    // Créer dynamiquement la keyframes
    const styleSheet = document.createElement('style');
    styleSheet.innerHTML = `@keyframes train-scroll { 0% { transform: translateX(-${blockWidth}px); } 100% { transform: translateX(0); } }`;
    document.head.appendChild(styleSheet);
});
</script>
<script>
window.addEventListener('load', function() {
    var navElement = document.getElementById('nav');
    var mainElement = document.getElementById('main');
    if (mainElement) {
        mainElement.classList.remove('hidden-until-loaded');
        mainElement.classList.add('show-after-load');
    }
    if (navElement) {
        navElement.classList.add('show-after-load');
    }
});
</script>