<?php
require('admin/db.php');
require_once __DIR__ . '/functions/images.php';

$id = $_GET['id'] ?? null;

function fallbackReferences(): array {
    return [
        ['id' => 1, 'titre' => 'Aménagement de la Place Jean Jaurès', 'commune' => 'APT (84)', 'domaine' => '5', 'statut' => '1', 'anneeD' => '2024', 'anneeF' => '2025', 'duree_travaux_mois' => '12', 'moa' => 'CITADIS (84)', 'archi' => 'XXX', 'eMoe' => 'XXX', 'montant' => 980000, 'nbPhase' => 'XXX', 'nbE' => 4, 'nombre_lots' => 6, 'description' => 'Aménagement de la Place Jean Jaurès à Apt.'],
        ['id' => 2, 'titre' => 'Centre Culturel Simone Signoret', 'commune' => 'CHATEAU-ARNOUX (04)', 'domaine' => '3', 'statut' => '1', 'anneeD' => '2023', 'anneeF' => '2025', 'duree_travaux_mois' => '15', 'moa' => 'PROVENCE ALPES AGGLOMERATION Digne-les-Bains (04)', 'archi' => 'XXX', 'eMoe' => 'XXX', 'montant' => 2875000, 'nbPhase' => 'XXX', 'nbE' => 13, 'nombre_lots' => 18, 'description' => 'Réhabilitation du Centre Culturel Simone Signoret et de ses abords à Château-Arnoux-Saint-Auban.'],
        ['id' => 3, 'titre' => 'L’hôtel des Monnaies-Niel', 'commune' => 'AVIGNON (84)', 'domaine' => '12', 'statut' => '2', 'anneeD' => '2024', 'anneeF' => '2027', 'duree_travaux_mois' => '20', 'moa' => 'Groupe E-Hôtel (69)', 'archi' => 'XXX', 'eMoe' => 'XXX', 'montant' => 8000000, 'nbPhase' => 'XXX', 'nbE' => 18, 'nombre_lots' => 25, 'description' => "Reconversion de l'hôtel des Monnaies-Niel pour la création d'un hôtel de 40 chambres, Place du Palais des Papes à Avignon."],
        ['id' => 4, 'titre' => "Residence l'Aygues", 'commune' => 'ORANGE (84)', 'domaine' => '10', 'statut' => '2', 'anneeD' => '2024', 'anneeF' => '2026', 'duree_travaux_mois' => '24', 'moa' => 'GRAND DELTA HABITAT (84)', 'archi' => 'XXX', 'eMoe' => 'XXX', 'montant' => 9250000, 'nbPhase' => 'XXX', 'nbE' => 9, 'nombre_lots' => 12, 'description' => "Réhabilitation de 146 logements collectifs - Résidence « L'Aygues » à Orange."],
        ['id' => 5, 'titre' => 'Réfectoire de Coudoux', 'commune' => 'COUDOUX (13)', 'domaine' => '4', 'statut' => '1', 'anneeD' => '2024', 'anneeF' => '2026', 'duree_travaux_mois' => '11', 'moa' => 'Commune de COUDOUX (13)', 'archi' => 'XXX', 'eMoe' => 'XXX', 'montant' => 1300000, 'nbPhase' => 'XXX', 'nbE' => 14, 'nombre_lots' => 16, 'description' => "Création d'un réfectoire et extension du groupe scolaire à Coudoux."],
        ['id' => 6, 'titre' => 'Réhabilitation du collège Paul Cézanne', 'commune' => 'BRIGNOLES (83)', 'domaine' => '4', 'statut' => '2', 'anneeD' => '2024', 'anneeF' => '2027', 'duree_travaux_mois' => '19', 'moa' => 'Conseil Départemental (83)', 'archi' => 'XXX', 'eMoe' => 'XXX', 'montant' => 5400000, 'nbPhase' => 'XXX', 'nbE' => 14, 'nombre_lots' => 18, 'description' => 'Réhabilitation du collège Paul Cézanne à Brignoles.'],
        ['id' => 7, 'titre' => 'Résidence Les Angevines', 'commune' => 'LE THOR (84)', 'domaine' => '10', 'statut' => '2', 'anneeD' => '2024', 'anneeF' => '2026', 'duree_travaux_mois' => '20', 'moa' => 'GRAND DELTA HABITAT (84)', 'archi' => 'XXX', 'eMoe' => 'XXX', 'montant' => 3280000, 'nbPhase' => 'XXX', 'nbE' => 17, 'nombre_lots' => 22, 'description' => 'Construction de 30 logements collectifs – Résidence « Les Angevines » au Thor.']
    ];
}

function getTheRef($db, $id) {
    $sql = "SELECT * FROM reference WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    $ref = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$ref && $db instanceof NullDb) {
        foreach (fallbackReferences() as $fallbackRef) {
            if ((string)$fallbackRef['id'] === (string)$id) return $fallbackRef;
        }
    }
    return $ref;
}

function getThePic($db, $idR) {
    $sql = "SELECT * FROM photo WHERE idR = :idR ORDER BY orderPic ASC";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':idR', $idR);
    $stmt->execute();
    $pics = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($pics) && $db instanceof NullDb) {
        $allPics = [
            1 => [
                'amenagement_de_la_place_jean_jaures_1.webp',
                'amenagement_de_la_place_jean_jaures_2.webp',
                'amenagement_de_la_place_jean_jaures_3.webp',
                'amenagement_de_la_place_jean_jaures_4.webp'
            ],
            2 => [
                'centre_culturel_simone_signoret_1.webp',
                'centre_culturel_simone_signoret_2.webp',
                'centre_culturel_simone_signoret_3.webp',
                'centre_culturel_simone_signoret_4.webp',
                'centre_culturel_simone_signoret_5.webp',
                'centre_culturel_simone_signoret_6.webp',
                'centre_culturel_simone_signoret_7.webp'
            ],
            3 => [
                'l_hotel_des_monnaies_niel_1.webp',
                'l_hotel_des_monnaies_niel_2.webp',
                'l_hotel_des_monnaies_niel_3.webp',
                'l_hotel_des_monnaies_niel_4.webp',
                'l_hotel_des_monnaies_niel_5.webp',
                'l_hotel_des_monnaies_niel_6.webp',
                'l_hotel_des_monnaies_niel_7.webp',
                'l_hotel_des_monnaies_niel_8.webp',
                'l_hotel_des_monnaies_niel_9.webp'
            ],
            4 => [
                'residence_l_aygues_1.webp'
            ],
            5 => [
                'refectoire_de_coudoux_1.webp',
                'refectoire_de_coudoux_2.webp',
                'refectoire_de_coudoux_3.webp',
                'refectoire_de_coudoux_4.webp',
                'refectoire_de_coudoux_5.webp',
                'refectoire_de_coudoux_6.webp',
                'refectoire_de_coudoux_7.webp'
            ],
            6 => [
                'rehabilitation_du_college_paul_cezanne_1.webp',
                'rehabilitation_du_college_paul_cezanne_2.webp',
                'rehabilitation_du_college_paul_cezanne_3.webp',
                'rehabilitation_du_college_paul_cezanne_4.webp',
                'rehabilitation_du_college_paul_cezanne_5.webp',
                'rehabilitation_du_college_paul_cezanne_6.webp'
            ],
            7 => [
                'residence_les_angevines_1.webp',
                'residence_les_angevines_2.webp',
                'residence_les_angevines_3.webp',
                'residence_les_angevines_4.webp',
                'residence_les_angevines_5.webp',
                'residence_les_angevines_6.webp',
                'residence_les_angevines_7.webp',
                'residence_les_angevines_8.webp'
            ]
        ];
        $list = $allPics[(int)$idR] ?? [];
        $out = [];
        foreach ($list as $idx => $pic) {
            $out[] = ['id' => $idx + 1, 'idR' => $idR, 'titre' => $pic, 'orderPic' => $idx + 1];
        }
        return apsiWebpPhotos($out);
    }
    return apsiWebpPhotos($pics);
}

function getSameDomainReferences($db, $domaine, $currentId) {
    $sql = "SELECT * FROM reference WHERE domaine = :domaine AND id != :currentId LIMIT 4";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':domaine', $domaine);
    $stmt->bindValue(':currentId', $currentId);
    $stmt->execute();
    $refs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($refs) && $db instanceof NullDb) {
        return array_slice(array_values(array_filter(fallbackReferences(), fn($ref) => (string)$ref['id'] !== (string)$currentId)), 0, 4);
    }
    return $refs;
}

function getAllPics($db): array {
    $sql = "SELECT * FROM photo ORDER BY orderPic ASC";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $pics = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($pics) && $db instanceof NullDb) {
        $allPics = [
            1 => [
                'amenagement_de_la_place_jean_jaures_1.webp',
                'amenagement_de_la_place_jean_jaures_2.webp',
                'amenagement_de_la_place_jean_jaures_3.webp',
                'amenagement_de_la_place_jean_jaures_4.webp'
            ],
            2 => [
                'centre_culturel_simone_signoret_1.webp',
                'centre_culturel_simone_signoret_2.webp',
                'centre_culturel_simone_signoret_3.webp',
                'centre_culturel_simone_signoret_4.webp',
                'centre_culturel_simone_signoret_5.webp',
                'centre_culturel_simone_signoret_6.webp',
                'centre_culturel_simone_signoret_7.webp'
            ],
            3 => [
                'l_hotel_des_monnaies_niel_1.webp',
                'l_hotel_des_monnaies_niel_2.webp',
                'l_hotel_des_monnaies_niel_3.webp',
                'l_hotel_des_monnaies_niel_4.webp',
                'l_hotel_des_monnaies_niel_5.webp',
                'l_hotel_des_monnaies_niel_6.webp',
                'l_hotel_des_monnaies_niel_7.webp',
                'l_hotel_des_monnaies_niel_8.webp',
                'l_hotel_des_monnaies_niel_9.webp'
            ],
            4 => [
                'residence_l_aygues_1.webp'
            ],
            5 => [
                'refectoire_de_coudoux_1.webp',
                'refectoire_de_coudoux_2.webp',
                'refectoire_de_coudoux_3.webp',
                'refectoire_de_coudoux_4.webp',
                'refectoire_de_coudoux_5.webp',
                'refectoire_de_coudoux_6.webp',
                'refectoire_de_coudoux_7.webp'
            ],
            6 => [
                'rehabilitation_du_college_paul_cezanne_1.webp',
                'rehabilitation_du_college_paul_cezanne_2.webp',
                'rehabilitation_du_college_paul_cezanne_3.webp',
                'rehabilitation_du_college_paul_cezanne_4.webp',
                'rehabilitation_du_college_paul_cezanne_5.webp',
                'rehabilitation_du_college_paul_cezanne_6.webp'
            ],
            7 => [
                'residence_les_angevines_1.webp',
                'residence_les_angevines_2.webp',
                'residence_les_angevines_3.webp',
                'residence_les_angevines_4.webp',
                'residence_les_angevines_5.webp',
                'residence_les_angevines_6.webp',
                'residence_les_angevines_7.webp',
                'residence_les_angevines_8.webp'
            ]
        ];
        $out = [];
        foreach ($allPics as $idR => $list) {
            foreach ($list as $idx => $pic) {
                $out[] = ['idR' => $idR, 'titre' => $pic, 'orderPic' => $idx + 1];
            }
        }
        return apsiWebpPhotos($out);
    }
    return apsiWebpPhotos($pics);
}

/** Missing project information stays visible, without writing placeholders to the CRM. */
function referenceDisplayValue($value): string {
    return $value === null || trim((string)$value) === '' ? 'XXX' : (string)$value;
}

function formatNumber($number) {
    return number_format((float)$number, 0, ',', ' ');
}

function domaineLabel($domaine): string {
    $labels = [
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
    return $labels[(string)$domaine] ?? '';
}

function safe_strtoupper(string $string): string {
    if (function_exists('mb_strtoupper')) {
        return mb_strtoupper($string, 'UTF-8');
    }
    $map = [
        'é' => 'É', 'è' => 'È', 'à' => 'À', 'ù' => 'Ù', 'ç' => 'Ç',
        'â' => 'Â', 'ê' => 'Ê', 'î' => 'Î', 'ô' => 'Ô', 'û' => 'Û',
        'ë' => 'Ë', 'ï' => 'Ï', 'ü' => 'Ü'
    ];
    return strtr(strtoupper($string), $map);
}

function statutLabel($statut): string {
    $labels = ['1' => 'Opération livrée', '2' => 'Travaux en cours', '3' => 'Conception en cours'];
    return $labels[(string)$statut] ?? '';
}

$ref = getTheRef($db, $id);
if (!$ref) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

$picTab = getThePic($db, $id);
$sameDomainRefs = !empty($ref['domaine']) ? getSameDomainReferences($db, $ref['domaine'], $id) : [];
$allPics = getAllPics($db);
$samePicByRef = [];
foreach ($allPics as $pic) {
    if (!isset($samePicByRef[$pic['idR']])) {
        $samePicByRef[$pic['idR']] = $pic['titre'];
    } elseif ((int)($pic['orderPic'] ?? 1) === 1) {
        $samePicByRef[$pic['idR']] = $pic['titre'];
    }
}

$mainPic = $picTab[0]['titre'] ?? 'img5.jpg';
$infoRows = [
    ['map-pin', 'Lieu', $ref['commune'] ?? null],
    ['building-2', 'Domaine', !empty($ref['domaine']) ? domaineLabel($ref['domaine']) : null],
    ['check-circle-2', 'Statut', !empty($ref['statut']) ? statutLabel($ref['statut']) : null],
    ['calendar-days', 'Période', !empty($ref['anneeD']) ? (($ref['anneeF'] ?? null) ? $ref['anneeD'] . ' - ' . $ref['anneeF'] . (!empty($ref['duree_travaux_mois']) ? ' (' . $ref['duree_travaux_mois'] . ' mois)' : '') : $ref['anneeD']) : null],
    ['user-round-cog', "Maître d'ouvrage", $ref['moa'] ?? null],
    ['pencil-ruler', 'Architecte', $ref['archi'] ?? null],
    ['users', "Équipe de Maîtrise d'Oeuvre", $ref['eMoe'] ?? null],
    ['euro', 'Montant des Travaux', !empty($ref['montant']) ? formatNumber($ref['montant']) . ' € HT' : null],
    ['layers-3', 'Nombre de phases', $ref['nbPhase'] ?? null],
    ['users-round', "Nombre d'entreprises", $ref['nbE'] ?? null],
    ['layout-grid', 'Nombre de lots', $ref['nombre_lots'] ?? null],
];

$displayPeriod = !empty($ref['anneeD']) || !empty($ref['anneeF'])
    ? referenceDisplayValue($ref['anneeD'] ?? null) . ' – ' . referenceDisplayValue($ref['anneeF'] ?? null) : 'XXX';
$displayDuration = isset($ref['duree_travaux_mois']) && trim((string)$ref['duree_travaux_mois']) !== ''
    ? $ref['duree_travaux_mois'] . ' mois' : 'XXX';
$displayBudget = isset($ref['montant']) && is_numeric($ref['montant']) ? formatNumber($ref['montant']) . ' € HT' : 'XXX';

$activePage = 'references';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= htmlspecialchars($ref['titre']) ?> - APSI BTP</title>
    <meta name="description" content="Découvrez le projet <?= htmlspecialchars($ref['titre']) ?> réalisé par APSI BTP à <?= htmlspecialchars($ref['commune'] ?? '') ?>.">
    <meta name="keywords" content="<?= htmlspecialchars($ref['titre']) ?>, <?= htmlspecialchars($ref['commune'] ?? '') ?>, OPC, MOEX, APSI BTP, chantier, suivi de travaux, PACA">
    <meta name="author" content="APSI BTP">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://apsi-btp.fr/reference/<?= (int)$id ?>">
    
    <!-- Open Graph -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="https://apsi-btp.fr/reference/<?= (int)$id ?>">
    <meta property="og:title" content="<?= htmlspecialchars($ref['titre']) ?> - APSI BTP">
    <meta property="og:description" content="Découvrez le projet <?= htmlspecialchars($ref['titre']) ?> réalisé par APSI BTP à <?= htmlspecialchars($ref['commune'] ?? '') ?>.">
    <meta property="og:image" content="https://apsi-btp.fr/img/logo-nav.webp">
    <meta property="og:image:type" content="image/webp">
    <meta property="og:image:width" content="1146">
    <meta property="og:image:height" content="652">
    <meta property="og:image:alt" content="APSI BTP - Coordination de travaux">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($ref['titre']) ?> - APSI BTP">
    <meta name="twitter:description" content="Découvrez le projet <?= htmlspecialchars($ref['titre']) ?> réalisé par APSI BTP à <?= htmlspecialchars($ref['commune'] ?? '') ?>.">
    <meta name="twitter:image" content="https://apsi-btp.fr/img/logo-nav.webp">
    <meta name="twitter:image:alt" content="APSI BTP - Coordination de travaux">

    <!-- Favicons & Manifest -->
    <?php require __DIR__ . '/partials/favicons.php'; ?>
    <!-- Fonts Preconnect -->

        <!-- Stylesheets (Inlined dynamically via PHP to prevent FOUC & maximize mobile performance) -->
        <?php require_once __DIR__ . '/functions/assets.php'; ?>
    <link data-apsi-style rel="stylesheet" href="<?= apsiAsset('/css/site.css') ?>">
    <link data-apsi-style rel="stylesheet" href="<?= apsiAsset('/css/reference.css') ?>">
    
    <!-- Fonts Preload -->
    <link rel="stylesheet" href="<?= apsiAsset('/css/fonts.css') ?>">
    <script src="<?= apsiAsset('/js/icons.min.js') ?>" defer></script>
    <script src="<?= apsiAsset('/js/navigation.js') ?>"></script>
    <script src="<?= apsiAsset('/js/site.js') ?>" defer></script>
    <?php include __DIR__ . '/partials/seo.php'; ?>
</head>
<body class="reference-page">
    <a class="skip-link" href="#main">Aller au contenu principal</a>
    <main id="main" tabindex="-1" class="reference-shell site-shell-bg">
        <div class="reference-top-gradient-wrapper">
            <?php include __DIR__ . '/partials/siteHeader.php'; ?>

            <!-- Content wrapper centering both Title and Showcase column layout together -->
            <div class="site-container">
                <section class="reference-heading">
                    <a href="/references" class="back-link">
                        <i data-lucide="arrow-left" aria-hidden="true"></i>Retour à nos références
                    </a>
                </section>

                <section class="reference-showcase">
                    <!-- Left Column: Immersive Image Gallery Container -->
                    <div class="reference-gallery">
                        <header class="reference-project-heading">
                            <h1 class="page-title"><?= htmlspecialchars($ref['titre']) ?></h1>
                            <div class="reference-project-meta">
                                <?php if (!empty($ref['domaine'])): ?>
                                    <span class="reference-badge"><i data-lucide="file-text" aria-hidden="true"></i><?= htmlspecialchars(safe_strtoupper(domaineLabel($ref['domaine']))) ?></span>
                                <?php endif; ?>
                                <?php if (!empty($ref['commune'])): ?>
                                    <span class="reference-location"><i data-lucide="map-pin" aria-hidden="true"></i><?= htmlspecialchars($ref['commune']) ?></span>
                                <?php endif; ?>
                            </div>
                        </header>
                        <div class="reference-main-image-container">
                            <button type="button" class="reference-fullscreen" aria-label="Agrandir la photo"><i data-lucide="expand" aria-hidden="true"></i></button>
                            <!-- Navigation Arrows -->
                            <button type="button" class="gallery-arrow gallery-arrow--left" aria-label="Image précédente">
                                <i data-lucide="chevron-left" aria-hidden="true"></i>
                            </button>
                            <?php
                            $main_pic_clean = htmlspecialchars($mainPic);
                            $img_basename = pathinfo($mainPic, PATHINFO_FILENAME);
                            $mobile_main_path = "pic/mobile/" . $img_basename . ".webp";
                            $has_mobile_main = file_exists(__DIR__ . '/' . $mobile_main_path);
                            $initial_mobile_srcset = $has_mobile_main ? '/' . $mobile_main_path : '/pic/' . $main_pic_clean;
                            ?>
                            <picture>
                                <source id="main-reference-source" media="(max-width: 980px)" srcset="<?= $initial_mobile_srcset ?>">
                                <img <?= apsiImageSize($mainPic) ?> id="main-reference-image" src="/pic/<?= $main_pic_clean ?>" alt="Projet <?= htmlspecialchars($ref['titre']) ?> à <?= htmlspecialchars($ref['commune'] ?? '') ?> - APSI BTP" fetchpriority="high">
                            </picture>

                            <button type="button" class="gallery-arrow gallery-arrow--right" aria-label="Image suivante">
                                <i data-lucide="chevron-right" aria-hidden="true"></i>
                            </button>

                            <div class="reference-image-overlay">
                                <!-- Slide Indicator: e.g., 01 -------- 06 -->
                                <?php if (count($picTab) > 1): ?>
                                    <div class="reference-slide-progress">
                                        <span id="current-slide-index">01</span>
                                        <span aria-hidden="true">/</span>
                                        <span><?= sprintf('%02d', count($picTab)) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Keep hidden thumbnail container so that JS list of sources works unmodified! -->
                        <div class="reference-thumbs" style="display: none;">
                            <?php foreach ($picTab as $index => $pic): ?>
                                <?php
                                $pic_name = htmlspecialchars($pic['titre']);
                                $thumb_basename = pathinfo($pic['titre'], PATHINFO_FILENAME);
                                $mobile_pic_path = "pic/mobile/" . $thumb_basename . ".webp";
                                $has_mobile_pic = file_exists(__DIR__ . '/' . $mobile_pic_path);
                                $mobile_src = $has_mobile_pic ? '/' . $mobile_pic_path : '/pic/' . $pic_name;
                                ?>
                                <button type="button" class="<?= $index === 0 ? 'active' : '' ?>" data-src="/pic/<?= $pic_name ?>" data-mobile-src="<?= $mobile_src ?>"></button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Right Column: Premium white info card -->
                    <aside class="reference-info-card">
                        <div class="card-header">
                            <h2>INTERVENANTS</h2>
                        </div>

                        <div class="card-body">
                            <div class="card-row">
                                <div class="row-label-container">
                                    <i data-lucide="hard-hat" aria-hidden="true"></i>
                                    <strong>Maître d'ouvrage</strong>
                                </div>
                                <span class="row-value"><?= htmlspecialchars(referenceDisplayValue($ref['moa'] ?? null)) ?></span>
                            </div>
                            <div class="card-row">
                                <div class="row-label-container">
                                    <i data-lucide="building-2" aria-hidden="true"></i>
                                    <strong>Architecte</strong>
                                </div>
                                <span class="row-value"><?= htmlspecialchars(referenceDisplayValue($ref['archi'] ?? null)) ?></span>
                            </div>
                            <div class="card-row">
                                <div class="row-label-container">
                                    <i data-lucide="boxes" aria-hidden="true"></i>
                                    <strong>Maîtrise d'œuvre</strong>
                                </div>
                                <span class="row-value"><?= htmlspecialchars(referenceDisplayValue($ref['eMoe'] ?? null)) ?></span>
                            </div>
                        </div>
                        <h2 class="reference-characteristics-title">CARACTÉRISTIQUES DU PROJET</h2>
                        <div class="reference-schedule">
                            <div class="schedule-item">
                                <i data-lucide="calendar-days" aria-hidden="true"></i>
                                <div><span>Période</span><strong><?= htmlspecialchars($displayPeriod) ?></strong></div>
                            </div>
                            <div class="schedule-item">
                                <i data-lucide="clock" aria-hidden="true"></i>
                                <div><span>Durée</span><strong><?= htmlspecialchars($displayDuration) ?></strong></div>
                            </div>
                            <div class="schedule-item">
                                <i data-lucide="circle-check" aria-hidden="true"></i>
                                <div><span>Statut</span><strong><?= !empty($ref['statut']) ? htmlspecialchars(referenceDisplayValue(statutLabel($ref['statut']))) : 'XXX' ?></strong></div>
                            </div>
                        </div>
                        <div class="reference-stats">
                            <?php foreach ([['layers-3', 'Phases', $ref['nbPhase'] ?? null], ['building', 'Entreprises', $ref['nbE'] ?? null], ['boxes', 'Lots', $ref['nombre_lots'] ?? null]] as [$icon, $label, $value]): ?>
                                <div class="reference-stat">
                                    <i data-lucide="<?= $icon ?>" aria-hidden="true"></i>
                                    <div><span><?= $label ?></span><strong><?= htmlspecialchars(referenceDisplayValue($value)) ?></strong></div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="reference-budget">
                            <i data-lucide="database" aria-hidden="true"></i>
                            <div><span>Montant des travaux</span><strong><?= htmlspecialchars($displayBudget) ?></strong></div>
                        </div>
                    </aside>
                </section>
            </div>
        </div>

        <?php if (!empty($sameDomainRefs)): ?>
            <section class="same-domain-section">
                <div class="site-container">
                    <h2>Suggestions de références</h2>
                    <div class="same-domain-grid">
                        <?php foreach($sameDomainRefs as $sameRef): ?>
                            <?php $img = $samePicByRef[$sameRef['id']] ?? null; ?>
                            <article class="same-domain-card">
                                <a href="/reference/<?= htmlspecialchars($sameRef['id']) ?>" aria-label="<?= htmlspecialchars($sameRef['titre']) ?>"></a>
                                <?php if ($img): ?>
                                    <?php
                                    $same_img_clean = htmlspecialchars(apsiCardImage($img));
                                    $same_basename = pathinfo($img, PATHINFO_FILENAME);
                                    $same_mobile_path = "pic/cards/" . $same_basename . ".webp";
                                    $same_has_mobile = file_exists(__DIR__ . '/' . $same_mobile_path);
                                    ?>
                                    <?php if ($same_has_mobile): ?>
                                        <picture>
                                            <source media="(max-width: 980px)" srcset="/<?= $same_mobile_path ?>">
                                            <img src="/pic/<?= $same_img_clean ?>" alt="Projet similaire - <?= htmlspecialchars($sameRef['titre']) ?> à <?= htmlspecialchars($sameRef['commune'] ?? '') ?> par APSI BTP" loading="lazy">
                                        </picture>
                                    <?php else: ?>
                                        <img src="/pic/<?= $same_img_clean ?>" alt="Projet similaire - <?= htmlspecialchars($sameRef['titre']) ?> à <?= htmlspecialchars($sameRef['commune'] ?? '') ?> par APSI BTP" loading="lazy">
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="same-empty">Aucune image disponible</div>
                                <?php endif; ?>
                                <div>
                                    <h3><?= htmlspecialchars($sameRef['titre']) ?></h3>
                                    <p><i data-lucide="map-pin" aria-hidden="true"></i><?= htmlspecialchars($sameRef['commune']) ?></p>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </main>

    <?php include __DIR__ . '/partials/siteFooter.php'; ?>

    <script>
    window.apsiPageReady(function() {
        if (window.lucide) window.lucide.createIcons();

        const titles = Array.from(document.querySelectorAll('.reference-project-heading .page-title, .card-header h2, .reference-characteristics-title, .same-domain-section h2, .same-domain-card h3'));
        const titleCanvas = document.createElement('canvas').getContext('2d');
        function fitTitles() {
            titles.forEach(title => {
                if (!title.isConnected || !title.clientWidth) return;
                title.style.removeProperty('font-size');
                const style = getComputedStyle(title);
                const maximum = parseFloat(style.fontSize);
                const text = title.textContent.trim();
                titleCanvas.font = style.fontWeight + ' ' + maximum + 'px ' + style.fontFamily;
                const spacing = parseFloat(style.letterSpacing) || 0;
                const textWidth = titleCanvas.measureText(text).width + Math.max(0, text.length - 1) * spacing;
                if (textWidth > title.clientWidth) {
                    title.style.fontSize = Math.max(1, maximum * title.clientWidth / textWidth * 0.995) + 'px';
                }
            });
        }
        fitTitles();
        document.fonts.ready.then(fitTitles);
        const titleWidths = new WeakMap();
        const titleObserver = new ResizeObserver(entries => {
            let widthChanged = false;
            for (const entry of entries) {
                const width = entry.contentRect.width;
                if (!entry.target.isConnected || width <= 0) continue;
                if (Math.abs(width - (titleWidths.get(entry.target) ?? -1)) > 0.5) {
                    titleWidths.set(entry.target, width);
                    widthChanged = true;
                }
            }
            // Font fitting changes height itself; observe width only to avoid a resize loop.
            if (widthChanged) fitTitles();
        });
        window.apsiPageSignal?.addEventListener('abort', () => titleObserver.disconnect(), { once: true });
        new Set(titles.map(title => title.parentElement)).forEach(parent => titleObserver.observe(parent));
    });

    window.apsiPageReady(function() {
        const gallery = document.querySelector('.reference-main-image-container');
        const info = document.querySelector('.reference-showcase > .reference-info-card');
        const galleryColumn = document.querySelector('.reference-gallery');
        const heading = document.querySelector('.reference-project-heading');
        const pageSignal = window.apsiPageSignal;
        // Freeze a complete panel, including all labels, as the sizing reference.
        // This hidden copy never depends on later visibility changes in the real panel.
        if (gallery && info && galleryColumn && heading) {
            const measure = document.createElement('div');
            measure.className = 'reference-info-card reference-info-measure';
            measure.setAttribute('aria-hidden', 'true');
            measure.inert = true;
            [...info.childNodes].forEach(node => measure.append(node.cloneNode(true)));
            measure.querySelectorAll('[id]').forEach(node => node.removeAttribute('id'));
            measure.querySelectorAll('[hidden]').forEach(node => node.removeAttribute('hidden'));
            document.body.append(measure);
            let frame = 0;
            function sizeGallery() {
                frame = 0;
                if (!gallery.isConnected || pageSignal?.aborted || document.fullscreenElement === gallery) return;
                if (window.innerWidth <= 980) {
                    galleryColumn.style.removeProperty('--reference-complete-info-height');
                    measure.style.display = 'none';
                    return;
                }
                measure.style.display = 'flex';
                const columns = getComputedStyle(info.parentElement).gridTemplateColumns.split(' ');
                const width = info.getBoundingClientRect().width || parseFloat(columns[1]);
                if (!width) return;
                measure.style.width = width + 'px';
                for (const selector of ['.card-header h2', '.reference-characteristics-title']) {
                    const actual = info.querySelector(selector);
                    if (actual && actual.getBoundingClientRect().width) measure.querySelector(selector).style.fontSize = getComputedStyle(actual).fontSize;
                }
                const height = Math.max(1, measure.getBoundingClientRect().height);
                const value = height.toFixed(3) + 'px';
                if (galleryColumn.style.getPropertyValue('--reference-complete-info-height') !== value) {
                    galleryColumn.style.setProperty('--reference-complete-info-height', value);
                }
            }
            function requestSize() { if (!frame) frame = requestAnimationFrame(sizeGallery); }
            const observer = new ResizeObserver(requestSize);
            observer.observe(info);
            observer.observe(heading);
            window.addEventListener('resize', requestSize, { signal: pageSignal });
            document.addEventListener('fullscreenchange', requestSize, { signal: pageSignal });
            document.fonts.ready.then(requestSize);
            pageSignal?.addEventListener('abort', () => { observer.disconnect(); cancelAnimationFrame(frame); measure.remove(); }, { once: true });
            sizeGallery();
        }
        const fullscreenButton = document.querySelector('.reference-fullscreen');
        if (gallery?.requestFullscreen) {
            fullscreenButton?.addEventListener('click', async () => {
                try {
                    if (document.fullscreenElement === gallery) await document.exitFullscreen();
                    else await gallery.requestFullscreen();
                } catch (error) { console.warn('Agrandissement indisponible', error); }
            });
            document.addEventListener('fullscreenchange', () => {
                fullscreenButton?.setAttribute('aria-label', document.fullscreenElement === gallery ? 'Quitter le plein écran' : 'Agrandir la photo');
            });
        } else if (fullscreenButton) fullscreenButton.hidden = true;
        const image = document.getElementById('main-reference-image');
        const thumbs = Array.from(document.querySelectorAll('.reference-thumbs button'));
        let current = 0;

        function show(index) {
            if (!image || thumbs.length === 0) return;
            current = (index + thumbs.length) % thumbs.length;
            thumbs.forEach((thumb, thumbIndex) => thumb.classList.toggle('active', thumbIndex === current));
            image.src = thumbs[current].dataset.src;
            const source = document.getElementById('main-reference-source');
            if (source) {
                source.srcset = thumbs[current].dataset.mobileSrc || thumbs[current].dataset.src;
            }

            // Update 2-digit slide progress text (e.g. 01, 02)
            const indicator = document.getElementById('current-slide-index');
            if (indicator) {
                indicator.textContent = String(current + 1).padStart(2, '0');
            }
        }

        thumbs.forEach((thumb, index) => thumb.addEventListener('click', () => show(index)));
        document.querySelector('.gallery-arrow--left')?.addEventListener('click', () => show(current - 1));
        document.querySelector('.gallery-arrow--right')?.addEventListener('click', () => show(current + 1));
    });
    </script>
</body>
</html>
