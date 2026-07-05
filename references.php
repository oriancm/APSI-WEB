<?php
require('admin/db.php');

function getAllRef($db) {
    $sql = "SELECT * FROM reference ORDER BY id ASC";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $refs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($refs) && $db instanceof NullDb) {
        return [
            ['id' => 1, 'titre' => 'Aménagement de la Place Jean Jaurès', 'commune' => 'APT (84)', 'domaine' => '5'],
            ['id' => 2, 'titre' => 'Centre Culturel Simone Signoret', 'commune' => 'CHATEAU-ARNOUX (04)', 'domaine' => '3'],
            ['id' => 3, 'titre' => 'L’hôtel des Monnaies-Niel', 'commune' => 'AVIGNON (84)', 'domaine' => '12'],
            ['id' => 4, 'titre' => "Residence l'Aygues", 'commune' => 'ORANGE (84)', 'domaine' => '10'],
            ['id' => 5, 'titre' => 'Réfectoire de Coudoux', 'commune' => 'COUDOUX (13)', 'domaine' => '4'],
            ['id' => 6, 'titre' => 'Réhabilitation du collège Paul Cézanne', 'commune' => 'BRIGNOLES (83)', 'domaine' => '4'],
            ['id' => 7, 'titre' => 'Résidence Les Angevines', 'commune' => 'LE THOR (84)', 'domaine' => '10']
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
        return [
            ['idR' => 1, 'titre' => 'amenagement_de_la_place_jean_jaures_1.jpg', 'orderPic' => 1],
            ['idR' => 1, 'titre' => 'amenagement_de_la_place_jean_jaures_2.png', 'orderPic' => 2],
            ['idR' => 1, 'titre' => 'amenagement_de_la_place_jean_jaures_3.png', 'orderPic' => 3],
            ['idR' => 1, 'titre' => 'amenagement_de_la_place_jean_jaures_4.jpg', 'orderPic' => 4],
            ['idR' => 2, 'titre' => 'centre_culturel_simone_signoret_1.jpg', 'orderPic' => 1],
            ['idR' => 2, 'titre' => 'centre_culturel_simone_signoret_2.jpg', 'orderPic' => 2],
            ['idR' => 2, 'titre' => 'centre_culturel_simone_signoret_3.jpg', 'orderPic' => 3],
            ['idR' => 2, 'titre' => 'centre_culturel_simone_signoret_4.jpg', 'orderPic' => 4],
            ['idR' => 2, 'titre' => 'centre_culturel_simone_signoret_5.jpg', 'orderPic' => 5],
            ['idR' => 2, 'titre' => 'centre_culturel_simone_signoret_6.jpg', 'orderPic' => 6],
            ['idR' => 2, 'titre' => 'centre_culturel_simone_signoret_7.jpg', 'orderPic' => 7],
            ['idR' => 3, 'titre' => 'l_hotel_des_monnaies_niel_1.jpg', 'orderPic' => 1],
            ['idR' => 3, 'titre' => 'l_hotel_des_monnaies_niel_2.png', 'orderPic' => 2],
            ['idR' => 3, 'titre' => 'l_hotel_des_monnaies_niel_3.jpg', 'orderPic' => 3],
            ['idR' => 3, 'titre' => 'l_hotel_des_monnaies_niel_4.jpg', 'orderPic' => 4],
            ['idR' => 3, 'titre' => 'l_hotel_des_monnaies_niel_5.jpg', 'orderPic' => 5],
            ['idR' => 3, 'titre' => 'l_hotel_des_monnaies_niel_6.jpg', 'orderPic' => 6],
            ['idR' => 3, 'titre' => 'l_hotel_des_monnaies_niel_7.jpg', 'orderPic' => 7],
            ['idR' => 3, 'titre' => 'l_hotel_des_monnaies_niel_8.jpg', 'orderPic' => 8],
            ['idR' => 3, 'titre' => 'l_hotel_des_monnaies_niel_9.jpg', 'orderPic' => 9],
            ['idR' => 4, 'titre' => 'residence_l_aygues_1.jpg', 'orderPic' => 1],
            ['idR' => 5, 'titre' => 'refectoire_de_coudoux_1.jpg', 'orderPic' => 1],
            ['idR' => 5, 'titre' => 'refectoire_de_coudoux_2.jpg', 'orderPic' => 2],
            ['idR' => 5, 'titre' => 'refectoire_de_coudoux_3.jpg', 'orderPic' => 3],
            ['idR' => 5, 'titre' => 'refectoire_de_coudoux_4.avif', 'orderPic' => 4],
            ['idR' => 5, 'titre' => 'refectoire_de_coudoux_5.jpg', 'orderPic' => 5],
            ['idR' => 5, 'titre' => 'refectoire_de_coudoux_6.jpg', 'orderPic' => 6],
            ['idR' => 5, 'titre' => 'refectoire_de_coudoux_7.jpg', 'orderPic' => 7],
            ['idR' => 6, 'titre' => 'rehabilitation_du_college_paul_cezanne_1.jpg', 'orderPic' => 1],
            ['idR' => 6, 'titre' => 'rehabilitation_du_college_paul_cezanne_2.jpg', 'orderPic' => 2],
            ['idR' => 6, 'titre' => 'rehabilitation_du_college_paul_cezanne_3.jpg', 'orderPic' => 3],
            ['idR' => 6, 'titre' => 'rehabilitation_du_college_paul_cezanne_4.jpg', 'orderPic' => 4],
            ['idR' => 6, 'titre' => 'rehabilitation_du_college_paul_cezanne_5.jpg', 'orderPic' => 5],
            ['idR' => 6, 'titre' => 'rehabilitation_du_college_paul_cezanne_6.jpg', 'orderPic' => 6],
            ['idR' => 7, 'titre' => 'residence_les_angevines_1.jpg', 'orderPic' => 1],
            ['idR' => 7, 'titre' => 'residence_les_angevines_2.jpg', 'orderPic' => 2],
            ['idR' => 7, 'titre' => 'residence_les_angevines_3.jpeg', 'orderPic' => 3],
            ['idR' => 7, 'titre' => 'residence_les_angevines_4.jpg', 'orderPic' => 4],
            ['idR' => 7, 'titre' => 'residence_les_angevines_5.jpg', 'orderPic' => 5],
            ['idR' => 7, 'titre' => 'residence_les_angevines_6.jpg', 'orderPic' => 6],
            ['idR' => 7, 'titre' => 'residence_les_angevines_7.jpg', 'orderPic' => 7],
            ['idR' => 7, 'titre' => 'residence_les_angevines_8.jpg', 'orderPic' => 8]
        ];
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
    <meta name="keywords" content="références BTP, réalisations BTP, projets construction, OPC, MOEX, APSI BTP, chantiers Provence, PACA">
    <meta name="author" content="APSI BTP">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://apsi-btp.fr/references">
    
    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://apsi-btp.fr/references">
    <meta property="og:title" content="Nos Références - APSI BTP | Projets BTP en Provence-Alpes-Côte d'Azur">
    <meta property="og:description" content="Découvrez nos références en Ordonnancement Pilotage Coordination (OPC) et Maîtrise d'Oeuvre d'Exécution (MOEX).">
    <meta property="og:image" content="https://apsi-btp.fr/img/APSI.png">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Nos Références - APSI BTP">
    <meta name="twitter:description" content="Découvrez nos références en Ordonnancement Pilotage Coordination (OPC) et Maîtrise d'Oeuvre d'Exécution (MOEX).">
    <meta name="twitter:image" content="https://apsi-btp.fr/img/APSI.png">

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
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
            <div class="references-filter-wrap">
                <button class="filter-toggle" type="button" aria-expanded="false" aria-controls="references-filter">
                    <span class="btn-content">
                        <i data-lucide="filter" aria-hidden="true"></i>
                        Filtrer par domaine
                    </span>
                    <i data-lucide="chevron-down" class="toggle-chevron" aria-hidden="true"></i>
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
            </div>

            <div class="references-grid" id="references-grid">
                <?php foreach ($refTab as $ref): ?>
                    <?php $img = $picByRef[$ref['id']] ?? null; ?>
                    <article class="reference-tile" data-domain="<?= htmlspecialchars($ref['domaine'] ?? '0') ?>">
                        <a href="/reference/<?= htmlspecialchars($ref['id']) ?>" aria-label="<?= htmlspecialchars($ref['titre']) ?>"></a>
                        <?php if ($img): ?>
                            <img src="/pic/<?= htmlspecialchars($img) ?>" alt="Projet <?= htmlspecialchars($ref['titre']) ?> à <?= htmlspecialchars($ref['commune']) ?> par APSI BTP" loading="lazy">
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

        filterToggle?.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = filterPanel.classList.toggle('is-open');
            filterToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (filterPanel?.classList.contains('is-open')) {
                if (!filterPanel.contains(e.target) && !filterToggle?.contains(e.target)) {
                    filterPanel.classList.remove('is-open');
                    filterToggle?.setAttribute('aria-expanded', 'false');
                }
            }
        });
    });
    </script>
</body>
</html>
