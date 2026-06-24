<?php
require('admin/db.php');

function getAllRef($db) {
    $sql = "SELECT * FROM reference ORDER BY id ASC";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $refs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($refs) && $db instanceof NullDb) {
        return [
            ['id' => 10001, 'titre' => 'Groupe scolaire en site occupé', 'commune' => 'Avignon', 'domaine' => '4'],
            ['id' => 10002, 'titre' => 'Réhabilitation de logements collectifs', 'commune' => 'Apt', 'domaine' => '10'],
            ['id' => 10003, 'titre' => 'Pôle santé et services publics', 'commune' => 'Manosque', 'domaine' => '9'],
            ['id' => 10004, 'titre' => 'Gymnase intercommunal', 'commune' => 'Pertuis', 'domaine' => '2'],
            ['id' => 10005, 'titre' => 'Équipement culturel et médiathèque', 'commune' => 'Cavaillon', 'domaine' => '3'],
            ['id' => 10006, 'titre' => 'Maison des associations', 'commune' => 'Forcalquier', 'domaine' => '6'],
            ['id' => 10007, 'titre' => 'Extension administrative', 'commune' => 'Sisteron', 'domaine' => '6'],
            ['id' => 10008, 'titre' => 'Équipement petite enfance', 'commune' => 'Salon-de-Provence', 'domaine' => '7'],
        ];
    }
    return $refs;
}

function getAllPic($db) {
    $sql = "SELECT * FROM photo ORDER BY orderPic ASC";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $pics = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($pics) && $db instanceof NullDb) {
        $localPics = ['img1.jpg', 'img2.jpg', 'img3.jpg', 'img4.jpg', 'img5.jpg', 'img6.jpg', 'img7.jpeg', 'img8.png'];
        $fallbackPics = [];
        foreach ($localPics as $index => $pic) {
            $fallbackPics[] = ['idR' => 10001 + $index, 'titre' => $pic, 'orderPic' => 1];
        }
        return $fallbackPics;
    }
    return $pics;
}

$refTab = getAllRef($db);
$picTab = getAllPic($db);
$picByRef = [];
foreach ($picTab as $pic) {
    if (!isset($picByRef[$pic['idR']])) {
        $picByRef[$pic['idR']] = $pic['titre'];
    } elseif ((int)($pic['orderPic'] ?? 1) === 1) {
        $picByRef[$pic['idR']] = $pic['titre'];
    }
}

$domains = [
    'all' => 'Tous les domaines',
    '1' => 'Résidences Universitaires',
    '2' => 'Équipements sportifs',
    '3' => 'Équipements culturels',
    '4' => 'Groupes scolaires et collèges',
    '5' => 'Aménagements urbains',
    '6' => 'Bâtiments publics',
    '7' => 'Crèches',
    '8' => "Centre d'Incendie et de Secours",
    '9' => 'Santé',
    '10' => 'Logements sociaux',
    '11' => 'Monuments Historiques',
    '12' => 'Restructuration et réhabilitation',
];

$activePage = 'references';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nos Références - APSI BTP | Projets BTP en Provence-Alpes-Côte d'Azur</title>
    <meta name="description" content="Découvrez nos références en Ordonnancement Pilotage Coordination (OPC) et Maîtrise d'Oeuvre d'Exécution (MOEX).">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="stylesheet" href="/css/site.css">
    <link rel="stylesheet" href="/css/references.css?v=20260623-1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>
    <script src="/js/site.js" defer></script>
</head>
<body class="references-page">
    <main class="references-shell site-shell-bg">
        <?php include __DIR__ . '/partials/siteHeader.php'; ?>

        <section class="references-hero site-container">
            <h1 class="page-title">Nos Références</h1>
            <span class="page-line"></span>
            <p>Découvrez une sélection de projets accompagnés par APSI BTP dans les secteurs <b>publics</b> et <b>privés</b>.</p>
        </section>

        <section class="references-layout site-container">
            <button class="filter-toggle" type="button" aria-expanded="false" aria-controls="references-filter">
                <i data-lucide="filter" aria-hidden="true"></i>
                Filtrer par domaine
            </button>

            <aside class="references-filter" id="references-filter" aria-label="Filtrer les références">
                <div class="filter-title">
                    <h2>Filtrer par domaine</h2>
                    <i data-lucide="filter" aria-hidden="true"></i>
                </div>
                <ul>
                    <?php foreach ($domains as $value => $label): ?>
                        <li><button class="<?= $value === 'all' ? 'active' : '' ?>" type="button" data-filter="<?= htmlspecialchars($value) ?>"><?= htmlspecialchars($label) ?></button></li>
                    <?php endforeach; ?>
                </ul>
            </aside>

            <div class="references-grid" id="references-grid">
                <?php foreach ($refTab as $ref): ?>
                    <?php $img = $picByRef[$ref['id']] ?? null; ?>
                    <article class="reference-tile" data-domain="<?= htmlspecialchars($ref['domaine'] ?? '0') ?>">
                        <a href="/reference/<?= htmlspecialchars($ref['id']) ?>" aria-label="<?= htmlspecialchars($ref['titre']) ?>"></a>
                        <?php if ($img): ?>
                            <img src="/pic/<?= htmlspecialchars($img) ?>" alt="">
                        <?php else: ?>
                            <div class="reference-tile-empty">Aucune image disponible</div>
                        <?php endif; ?>
                        <div>
                            <h2><?= htmlspecialchars($ref['titre']) ?></h2>
                            <p><i data-lucide="map-pin" aria-hidden="true"></i><?= htmlspecialchars($ref['commune']) ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/partials/siteFooter.php'; ?>

    <script>
    window.addEventListener('load', function() {
        if (window.lucide) window.lucide.createIcons();
    });

    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('.references-filter button[data-filter]');
        const cards = document.querySelectorAll('.reference-tile');
        const filterPanel = document.getElementById('references-filter');
        const filterToggle = document.querySelector('.filter-toggle');

        buttons.forEach((button) => {
            button.addEventListener('click', () => {
                buttons.forEach((item) => item.classList.remove('active'));
                button.classList.add('active');
                const filter = button.dataset.filter;
                cards.forEach((card) => {
                    card.hidden = filter !== 'all' && card.dataset.domain !== filter;
                });
                filterPanel?.classList.remove('is-open');
                filterToggle?.setAttribute('aria-expanded', 'false');
            });
        });

        filterToggle?.addEventListener('click', () => {
            const isOpen = filterPanel.classList.toggle('is-open');
            filterToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    });
    </script>
</body>
</html>
