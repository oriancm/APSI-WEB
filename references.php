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
            ['idR' => 1, 'titre' => 'amenagement_de_la_place_jean_jaures_1.avif', 'orderPic' => 1],
            ['idR' => 1, 'titre' => 'amenagement_de_la_place_jean_jaures_2.avif', 'orderPic' => 2],
            ['idR' => 1, 'titre' => 'amenagement_de_la_place_jean_jaures_3.avif', 'orderPic' => 3],
            ['idR' => 1, 'titre' => 'amenagement_de_la_place_jean_jaures_4.avif', 'orderPic' => 4],
            ['idR' => 2, 'titre' => 'centre_culturel_simone_signoret_1.avif', 'orderPic' => 1],
            ['idR' => 2, 'titre' => 'centre_culturel_simone_signoret_2.avif', 'orderPic' => 2],
            ['idR' => 2, 'titre' => 'centre_culturel_simone_signoret_3.avif', 'orderPic' => 3],
            ['idR' => 2, 'titre' => 'centre_culturel_simone_signoret_4.avif', 'orderPic' => 4],
            ['idR' => 2, 'titre' => 'centre_culturel_simone_signoret_5.avif', 'orderPic' => 5],
            ['idR' => 2, 'titre' => 'centre_culturel_simone_signoret_6.avif', 'orderPic' => 6],
            ['idR' => 2, 'titre' => 'centre_culturel_simone_signoret_7.avif', 'orderPic' => 7],
            ['idR' => 3, 'titre' => 'l_hotel_des_monnaies_niel_1.avif', 'orderPic' => 1],
            ['idR' => 3, 'titre' => 'l_hotel_des_monnaies_niel_2.avif', 'orderPic' => 2],
            ['idR' => 3, 'titre' => 'l_hotel_des_monnaies_niel_3.avif', 'orderPic' => 3],
            ['idR' => 3, 'titre' => 'l_hotel_des_monnaies_niel_4.avif', 'orderPic' => 4],
            ['idR' => 3, 'titre' => 'l_hotel_des_monnaies_niel_5.avif', 'orderPic' => 5],
            ['idR' => 3, 'titre' => 'l_hotel_des_monnaies_niel_6.avif', 'orderPic' => 6],
            ['idR' => 3, 'titre' => 'l_hotel_des_monnaies_niel_7.avif', 'orderPic' => 7],
            ['idR' => 3, 'titre' => 'l_hotel_des_monnaies_niel_8.avif', 'orderPic' => 8],
            ['idR' => 3, 'titre' => 'l_hotel_des_monnaies_niel_9.avif', 'orderPic' => 9],
            ['idR' => 4, 'titre' => 'residence_l_aygues_1.avif', 'orderPic' => 1],
            ['idR' => 5, 'titre' => 'refectoire_de_coudoux_1.avif', 'orderPic' => 1],
            ['idR' => 5, 'titre' => 'refectoire_de_coudoux_2.avif', 'orderPic' => 2],
            ['idR' => 5, 'titre' => 'refectoire_de_coudoux_3.avif', 'orderPic' => 3],
            ['idR' => 5, 'titre' => 'refectoire_de_coudoux_4.avif', 'orderPic' => 4],
            ['idR' => 5, 'titre' => 'refectoire_de_coudoux_5.avif', 'orderPic' => 5],
            ['idR' => 5, 'titre' => 'refectoire_de_coudoux_6.avif', 'orderPic' => 6],
            ['idR' => 5, 'titre' => 'refectoire_de_coudoux_7.avif', 'orderPic' => 7],
            ['idR' => 6, 'titre' => 'rehabilitation_du_college_paul_cezanne_1.avif', 'orderPic' => 1],
            ['idR' => 6, 'titre' => 'rehabilitation_du_college_paul_cezanne_2.avif', 'orderPic' => 2],
            ['idR' => 6, 'titre' => 'rehabilitation_du_college_paul_cezanne_3.avif', 'orderPic' => 3],
            ['idR' => 6, 'titre' => 'rehabilitation_du_college_paul_cezanne_4.avif', 'orderPic' => 4],
            ['idR' => 6, 'titre' => 'rehabilitation_du_college_paul_cezanne_5.avif', 'orderPic' => 5],
            ['idR' => 6, 'titre' => 'rehabilitation_du_college_paul_cezanne_6.avif', 'orderPic' => 6],
            ['idR' => 7, 'titre' => 'residence_les_angevines_1.avif', 'orderPic' => 1],
            ['idR' => 7, 'titre' => 'residence_les_angevines_2.avif', 'orderPic' => 2],
            ['idR' => 7, 'titre' => 'residence_les_angevines_3.avif', 'orderPic' => 3],
            ['idR' => 7, 'titre' => 'residence_les_angevines_4.avif', 'orderPic' => 4],
            ['idR' => 7, 'titre' => 'residence_les_angevines_5.avif', 'orderPic' => 5],
            ['idR' => 7, 'titre' => 'residence_les_angevines_6.avif', 'orderPic' => 6],
            ['idR' => 7, 'titre' => 'residence_les_angevines_7.avif', 'orderPic' => 7],
            ['idR' => 7, 'titre' => 'residence_les_angevines_8.avif', 'orderPic' => 8]
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

$featuredRefs = array_values(array_filter($refTab, fn($ref) => isset($picByRef[$ref['id']])));
usort($featuredRefs, function ($left, $right) {
    $leftRank = stripos($left['titre'], 'monnaies') !== false ? 0 : 1;
    $rightRank = stripos($right['titre'], 'monnaies') !== false ? 0 : 1;
    return ($leftRank <=> $rightRank) ?: ((int)$left['id'] <=> (int)$right['id']);
});
$featuredRefs = array_slice($featuredRefs, 0, 3);
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
    <meta property="og:image" content="https://apsi-btp.fr/img/logo-nav.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1146">
    <meta property="og:image:height" content="652">
    <meta property="og:image:alt" content="APSI BTP - Coordination de travaux">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Nos Références - APSI BTP">
    <meta name="twitter:description" content="Découvrez nos références en Ordonnancement Pilotage Coordination (OPC) et Maîtrise d'Oeuvre d'Exécution (MOEX).">
    <meta name="twitter:image" content="https://apsi-btp.fr/img/logo-nav.png">
    <meta name="twitter:image:alt" content="APSI BTP - Coordination de travaux">

    <!-- Favicons & Manifest -->
    <link rel="icon" type="image/png" sizes="48x48" href="/favicon-48x48.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/android-chrome-192x192.png">
    <link rel="icon" type="image/png" sizes="512x512" href="/android-chrome-512x512.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="manifest" href="/site.webmanifest">
    <!-- Fonts Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <!-- Stylesheets (Inlined dynamically via PHP to prevent FOUC & maximize mobile performance) -->
        <?php
    if (!function_exists('apsi_minify_css')) {
        function apsi_minify_css($path) {
            if (!file_exists($path)) return '';
            $css = file_get_contents($path);
            // Remove CSS comments
            $css = preg_replace('!/\*[^*]*\*+([^/*][^*]*\*+)*/!', '', $css);
            // Remove space around braces, colons, semi-colons
            $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    '), '', $css);
            $css = preg_replace('/(\s*([:;{}])\s*)/', '$2', $css);
            return $css;
        }
    }
    ?>
    <style>
        <?= apsi_minify_css(__DIR__ . '/css/site.css'); ?>
        <?= apsi_minify_css(__DIR__ . '/css/references.css'); ?>
        <?= apsi_minify_css(__DIR__ . '/css/hero-layout-test.css'); /* Temporary reversible hero test. */ ?>
    </style>
    
    <!-- Fonts Preload -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap">
    </noscript>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>
    <script src="/js/navigation.js"></script>
    <script src="/js/site.js" defer></script>
</head>
<body class="references-page">
    <main class="references-shell site-shell-bg">
        <?php include __DIR__ . '/partials/siteHeader.php'; ?>

        <section class="references-hero site-container" aria-labelledby="references-title">
            <div class="references-hero__copy">
                <p class="references-eyebrow">Nos références</p>
                <h1 id="references-title">Des projets variés,<br><span>un même engagement</span></h1>
                <p class="references-hero__description">Parcourez les opérations accompagnées par APSI BTP et filtrez nos références par domaine.</p>
                <a class="references-hero__browse" href="#references-layout">Parcourir les projets <i data-lucide="arrow-down" aria-hidden="true"></i></a>
            </div>
            <?php if ($featuredRefs): ?>
                <div class="references-carousel" role="region" aria-roledescription="carrousel" aria-label="Quelques références APSI BTP">
                    <?php foreach ($featuredRefs as $index => $featured): ?>
                        <div class="references-carousel__slide" role="group" aria-roledescription="diapositive" aria-label="<?= $index + 1 ?> sur <?= count($featuredRefs) ?>" <?= $index ? 'hidden' : '' ?>>
                            <img src="/pic/<?= htmlspecialchars($picByRef[$featured['id']]) ?>" alt="<?= htmlspecialchars($featured['titre']) ?> à <?= htmlspecialchars($featured['commune']) ?>" <?= $index === 0 ? 'fetchpriority="high"' : 'loading="lazy"' ?>>
                            <a class="references-carousel__caption" href="/reference/<?= htmlspecialchars($featured['id']) ?>">
                                <strong><?= htmlspecialchars($featured['titre']) ?></strong>
                                <span><i data-lucide="map-pin" aria-hidden="true"></i><?= htmlspecialchars($featured['commune']) ?></span>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <section class="references-layout site-container" id="references-layout" aria-label="Nos réalisations par domaine">
            <div class="references-filter-wrap">
                <p class="references-results" role="status" aria-live="polite" aria-atomic="true"><span class="references-results__domain">Tous les domaines</span> · <span class="references-results__count"><?= count($refTab) ?> résultats</span></p>
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
                            <li><button class="<?= $value === 'all' ? 'active' : '' ?>" type="button" aria-pressed="<?= $value === 'all' ? 'true' : 'false' ?>" data-filter="<?= htmlspecialchars($value) ?>"><?= htmlspecialchars($label) ?></button></li>
                        <?php endforeach; ?>
                    </ul>
                </aside>
            </div>

            <div class="references-grid" id="references-grid">
                <?php 
                $is_first = true;
                foreach ($refTab as $ref): 
                    $img = $picByRef[$ref['id']] ?? null; 
                ?>
                    <article class="reference-tile" data-domain="<?= htmlspecialchars($ref['domaine'] ?? '0') ?>">
                        <a href="/reference/<?= htmlspecialchars($ref['id']) ?>" aria-label="<?= htmlspecialchars($ref['titre']) ?>"></a>
                        <?php if (isset($domains[$ref['domaine'] ?? '0'])): ?>
                            <span class="reference-tile__domain"><?= htmlspecialchars($domains[$ref['domaine']]) ?></span>
                        <?php endif; ?>
                        <?php if ($img): ?>
                            <?php
                            $img_clean = htmlspecialchars($img);
                            $img_basename = pathinfo($img, PATHINFO_FILENAME);
                            $mobile_img_path = "pic/mobile/" . $img_basename . ".avif";
                            $has_mobile = file_exists(__DIR__ . '/' . $mobile_img_path);
                            $lazy_attr = $is_first ? 'fetchpriority="high"' : 'loading="lazy"';
                            ?>
                            <?php if ($has_mobile): ?>
                                <picture>
                                    <source media="(max-width: 980px)" srcset="/<?= $mobile_img_path ?>">
                                    <img src="/pic/<?= $img_clean ?>" alt="Projet <?= htmlspecialchars($ref['titre']) ?> à <?= htmlspecialchars($ref['commune']) ?> par APSI BTP" <?= $lazy_attr ?>>
                                </picture>
                            <?php else: ?>
                                <img src="/pic/<?= $img_clean ?>" alt="Projet <?= htmlspecialchars($ref['titre']) ?> à <?= htmlspecialchars($ref['commune']) ?> par APSI BTP" <?= $lazy_attr ?>>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="reference-tile-empty">Aucune image disponible</div>
                        <?php endif; ?>
                        <span class="reference-tile__arrow" aria-hidden="true"><i data-lucide="arrow-right"></i></span>
                        <div>
                            <h2><?= htmlspecialchars($ref['titre']) ?></h2>
                            <p><i data-lucide="map-pin" aria-hidden="true"></i><?= htmlspecialchars($ref['commune']) ?></p>
                        </div>
                    </article>
                    <?php $is_first = false; ?>
                <?php endforeach; ?>
                <p class="references-empty" hidden role="status">Aucune référence dans ce domaine pour le moment.</p>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/partials/siteFooter.php'; ?>

    <script src="/js/references.js" defer></script>
</body>
</html>
