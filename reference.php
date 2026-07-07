<?php
require('admin/db.php');

$id = $_GET['id'] ?? null;

function fallbackReferences(): array {
    return [
        ['id' => 1, 'titre' => 'Aménagement de la Place Jean Jaurès', 'commune' => 'APT (84)', 'domaine' => '5', 'statut' => '1', 'anneeD' => '2024', 'anneeF' => '2025', 'duree_travaux_mois' => '12', 'moa' => 'CITADIS (84)', 'archi' => null, 'eMoe' => null, 'montant' => 980000, 'nbPhase' => null, 'nbE' => 4, 'nombre_lots' => 6, 'description' => 'Aménagement de la Place Jean Jaurès à Apt.'],
        ['id' => 2, 'titre' => 'Centre Culturel Simone Signoret', 'commune' => 'CHATEAU-ARNOUX (04)', 'domaine' => '3', 'statut' => '1', 'anneeD' => '2023', 'anneeF' => '2025', 'duree_travaux_mois' => '15', 'moa' => 'PROVENCE ALPES AGGLOMERATION Digne-les-Bains (04)', 'archi' => null, 'eMoe' => null, 'montant' => 2875000, 'nbPhase' => null, 'nbE' => 13, 'nombre_lots' => 18, 'description' => 'Réhabilitation du Centre Culturel Simone Signoret et de ses abords à Château-Arnoux-Saint-Auban.'],
        ['id' => 3, 'titre' => 'L’hôtel des Monnaies-Niel', 'commune' => 'AVIGNON (84)', 'domaine' => '12', 'statut' => '2', 'anneeD' => '2024', 'anneeF' => '2027', 'duree_travaux_mois' => '20', 'moa' => 'Groupe E-Hôtel (69)', 'archi' => null, 'eMoe' => null, 'montant' => 8000000, 'nbPhase' => null, 'nbE' => 18, 'nombre_lots' => 25, 'description' => "Reconversion de l'hôtel des Monnaies-Niel pour la création d'un hôtel de 40 chambres, Place du Palais des Papes à Avignon."],
        ['id' => 4, 'titre' => "Residence l'Aygues", 'commune' => 'ORANGE (84)', 'domaine' => '10', 'statut' => '2', 'anneeD' => '2024', 'anneeF' => '2026', 'duree_travaux_mois' => '24', 'moa' => 'GRAND DELTA HABITAT (84)', 'archi' => null, 'eMoe' => null, 'montant' => 9250000, 'nbPhase' => null, 'nbE' => 9, 'nombre_lots' => 12, 'description' => "Réhabilitation de 146 logements collectifs - Résidence « L'Aygues » à Orange."],
        ['id' => 5, 'titre' => 'Réfectoire de Coudoux', 'commune' => 'COUDOUX (13)', 'domaine' => '4', 'statut' => '1', 'anneeD' => '2024', 'anneeF' => '2026', 'duree_travaux_mois' => '11', 'moa' => 'Commune de COUDOUX (13)', 'archi' => null, 'eMoe' => null, 'montant' => 1300000, 'nbPhase' => null, 'nbE' => 14, 'nombre_lots' => 16, 'description' => "Création d'un réfectoire et extension du groupe scolaire à Coudoux."],
        ['id' => 6, 'titre' => 'Réhabilitation du collège Paul Cézanne', 'commune' => 'BRIGNOLES (83)', 'domaine' => '4', 'statut' => '2', 'anneeD' => '2024', 'anneeF' => '2027', 'duree_travaux_mois' => '19', 'moa' => 'Conseil Départemental (83)', 'archi' => null, 'eMoe' => null, 'montant' => 5400000, 'nbPhase' => null, 'nbE' => 14, 'nombre_lots' => 18, 'description' => 'Réhabilitation du collège Paul Cézanne à Brignoles.'],
        ['id' => 7, 'titre' => 'Résidence Les Angevines', 'commune' => 'LE THOR (84)', 'domaine' => '10', 'statut' => '2', 'anneeD' => '2024', 'anneeF' => '2026', 'duree_travaux_mois' => '20', 'moa' => 'GRAND DELTA HABITAT (84)', 'archi' => null, 'eMoe' => null, 'montant' => 3280000, 'nbPhase' => null, 'nbE' => 17, 'nombre_lots' => 22, 'description' => 'Construction de 30 logements collectifs – Résidence « Les Angevines » au Thor.']
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
                'amenagement_de_la_place_jean_jaures_1.jpg',
                'amenagement_de_la_place_jean_jaures_2.png',
                'amenagement_de_la_place_jean_jaures_3.png',
                'amenagement_de_la_place_jean_jaures_4.jpg'
            ],
            2 => [
                'centre_culturel_simone_signoret_1.jpg',
                'centre_culturel_simone_signoret_2.jpg',
                'centre_culturel_simone_signoret_3.jpg',
                'centre_culturel_simone_signoret_4.jpg',
                'centre_culturel_simone_signoret_5.jpg',
                'centre_culturel_simone_signoret_6.jpg',
                'centre_culturel_simone_signoret_7.jpg'
            ],
            3 => [
                'l_hotel_des_monnaies_niel_1.jpg',
                'l_hotel_des_monnaies_niel_2.png',
                'l_hotel_des_monnaies_niel_3.jpg',
                'l_hotel_des_monnaies_niel_4.jpg',
                'l_hotel_des_monnaies_niel_5.jpg',
                'l_hotel_des_monnaies_niel_6.jpg',
                'l_hotel_des_monnaies_niel_7.jpg',
                'l_hotel_des_monnaies_niel_8.jpg',
                'l_hotel_des_monnaies_niel_9.jpg'
            ],
            4 => [
                'residence_l_aygues_1.jpg'
            ],
            5 => [
                'refectoire_de_coudoux_1.jpg',
                'refectoire_de_coudoux_2.jpg',
                'refectoire_de_coudoux_3.jpg',
                'refectoire_de_coudoux_4.avif',
                'refectoire_de_coudoux_5.jpg',
                'refectoire_de_coudoux_6.jpg',
                'refectoire_de_coudoux_7.jpg'
            ],
            6 => [
                'rehabilitation_du_college_paul_cezanne_1.jpg',
                'rehabilitation_du_college_paul_cezanne_2.jpg',
                'rehabilitation_du_college_paul_cezanne_3.jpg',
                'rehabilitation_du_college_paul_cezanne_4.jpg',
                'rehabilitation_du_college_paul_cezanne_5.jpg',
                'rehabilitation_du_college_paul_cezanne_6.jpg'
            ],
            7 => [
                'residence_les_angevines_1.jpg',
                'residence_les_angevines_2.jpg',
                'residence_les_angevines_3.jpeg',
                'residence_les_angevines_4.jpg',
                'residence_les_angevines_5.jpg',
                'residence_les_angevines_6.jpg',
                'residence_les_angevines_7.jpg',
                'residence_les_angevines_8.jpg'
            ]
        ];
        $list = $allPics[(int)$idR] ?? [];
        $out = [];
        foreach ($list as $idx => $pic) {
            $out[] = ['id' => $idx + 1, 'idR' => $idR, 'titre' => $pic, 'orderPic' => $idx + 1];
        }
        return $out;
    }
    return $pics;
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
                'amenagement_de_la_place_jean_jaures_1.jpg',
                'amenagement_de_la_place_jean_jaures_2.png',
                'amenagement_de_la_place_jean_jaures_3.png',
                'amenagement_de_la_place_jean_jaures_4.jpg'
            ],
            2 => [
                'centre_culturel_simone_signoret_1.jpg',
                'centre_culturel_simone_signoret_2.jpg',
                'centre_culturel_simone_signoret_3.jpg',
                'centre_culturel_simone_signoret_4.jpg',
                'centre_culturel_simone_signoret_5.jpg',
                'centre_culturel_simone_signoret_6.jpg',
                'centre_culturel_simone_signoret_7.jpg'
            ],
            3 => [
                'l_hotel_des_monnaies_niel_1.jpg',
                'l_hotel_des_monnaies_niel_2.png',
                'l_hotel_des_monnaies_niel_3.jpg',
                'l_hotel_des_monnaies_niel_4.jpg',
                'l_hotel_des_monnaies_niel_5.jpg',
                'l_hotel_des_monnaies_niel_6.jpg',
                'l_hotel_des_monnaies_niel_7.jpg',
                'l_hotel_des_monnaies_niel_8.jpg',
                'l_hotel_des_monnaies_niel_9.jpg'
            ],
            4 => [
                'residence_l_aygues_1.jpg'
            ],
            5 => [
                'refectoire_de_coudoux_1.jpg',
                'refectoire_de_coudoux_2.jpg',
                'refectoire_de_coudoux_3.jpg',
                'refectoire_de_coudoux_4.avif',
                'refectoire_de_coudoux_5.jpg',
                'refectoire_de_coudoux_6.jpg',
                'refectoire_de_coudoux_7.jpg'
            ],
            6 => [
                'rehabilitation_du_college_paul_cezanne_1.jpg',
                'rehabilitation_du_college_paul_cezanne_2.jpg',
                'rehabilitation_du_college_paul_cezanne_3.jpg',
                'rehabilitation_du_college_paul_cezanne_4.jpg',
                'rehabilitation_du_college_paul_cezanne_5.jpg',
                'rehabilitation_du_college_paul_cezanne_6.jpg'
            ],
            7 => [
                'residence_les_angevines_1.jpg',
                'residence_les_angevines_2.jpg',
                'residence_les_angevines_3.jpeg',
                'residence_les_angevines_4.jpg',
                'residence_les_angevines_5.jpg',
                'residence_les_angevines_6.jpg',
                'residence_les_angevines_7.jpg',
                'residence_les_angevines_8.jpg'
            ]
        ];
        $out = [];
        foreach ($allPics as $idR => $list) {
            foreach ($list as $idx => $pic) {
                $out[] = ['idR' => $idR, 'titre' => $pic, 'orderPic' => $idx + 1];
            }
        }
        return $out;
    }
    return $pics;
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
    header('Location: /references');
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
    <meta property="og:image" content="https://apsi-btp.fr/pic/<?= htmlspecialchars($mainPic) ?>">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($ref['titre']) ?> - APSI BTP">
    <meta name="twitter:description" content="Découvrez le projet <?= htmlspecialchars($ref['titre']) ?> réalisé par APSI BTP à <?= htmlspecialchars($ref['commune'] ?? '') ?>.">
    <meta name="twitter:image" content="https://apsi-btp.fr/pic/<?= htmlspecialchars($mainPic) ?>">

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="stylesheet" href="/css/site.css">
    <link rel="stylesheet" href="/css/reference.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>
    <script src="/js/site.js" defer></script>
</head>
<body class="reference-page">
    <main class="reference-shell site-shell-bg">
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
                        <div class="reference-main-image-container">
                            <!-- Category Badge -->
                            <?php if (!empty($ref['domaine'])): ?>
                                <div class="reference-badge">
                                    <i data-lucide="file-text" aria-hidden="true"></i>
                                    <span><?= htmlspecialchars(safe_strtoupper(domaineLabel($ref['domaine']))) ?></span>
                                </div>
                            <?php endif; ?>

                            <!-- Navigation Arrows -->
                            <button type="button" class="gallery-arrow gallery-arrow--left" aria-label="Image précédente">
                                <i data-lucide="chevron-left" aria-hidden="true"></i>
                            </button>
                            
                            <img id="main-reference-image" src="/pic/<?= htmlspecialchars($mainPic) ?>" alt="Projet <?= htmlspecialchars($ref['titre']) ?> à <?= htmlspecialchars($ref['commune'] ?? '') ?> - APSI BTP">

                            <button type="button" class="gallery-arrow gallery-arrow--right" aria-label="Image suivante">
                                <i data-lucide="chevron-right" aria-hidden="true"></i>
                            </button>

                            <!-- Bottom Title and Location Overlay -->
                            <div class="reference-image-overlay">
                                <h1 class="page-title"><?= htmlspecialchars($ref['titre']) ?></h1>
                                <?php if (!empty($ref['commune'])): ?>
                                    <p class="reference-location"><?= htmlspecialchars($ref['commune']) ?></p>
                                <?php endif; ?>

                                <!-- Slide Indicator: e.g., 01 -------- 06 -->
                                <?php if (count($picTab) > 1): ?>
                                    <div class="reference-slide-progress">
                                        <span id="current-slide-index">01</span>
                                        <span class="progress-line"></span>
                                        <span><?= sprintf('%02d', count($picTab)) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Keep hidden thumbnail container so that JS list of sources works unmodified! -->
                        <div class="reference-thumbs" style="display: none;">
                            <?php foreach ($picTab as $index => $pic): ?>
                                <button type="button" class="<?= $index === 0 ? 'active' : '' ?>" data-src="/pic/<?= htmlspecialchars($pic['titre']) ?>">
                                    <img src="/pic/<?= htmlspecialchars($pic['titre']) ?>" alt="Photo <?= $index + 1 ?> - <?= htmlspecialchars($ref['titre']) ?>">
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Right Column: Premium white info card -->
                    <aside class="reference-info-card">
                        <div class="card-header">
                            <h2>INFORMATIONS CLÉS</h2>
                        </div>

                        <div class="card-body">
                            <div class="card-row">
                                <div class="row-label-container">
                                    <i data-lucide="user" aria-hidden="true"></i>
                                    <strong>Maître d'ouvrage</strong>
                                </div>
                                <span class="row-value"><?= htmlspecialchars($ref['moa'] ?? '—') ?></span>
                            </div>
                            <?php if (!empty($ref['archi'])): ?>
                            <div class="card-row">
                                <div class="row-label-container">
                                    <i data-lucide="pencil" aria-hidden="true"></i>
                                    <strong>Architecte</strong>
                                </div>
                                <span class="row-value"><?= htmlspecialchars($ref['archi']) ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($ref['eMoe'])): ?>
                            <div class="card-row">
                                <div class="row-label-container">
                                    <i data-lucide="users" aria-hidden="true"></i>
                                    <strong>Équipe de Maîtrise d'Œuvre</strong>
                                </div>
                                <span class="row-value"><?= htmlspecialchars($ref['eMoe']) ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($ref['nbPhase'])): ?>
                            <div class="card-row">
                                <div class="row-label-container">
                                    <i data-lucide="git-branch" aria-hidden="true"></i>
                                    <strong>Nombre de phases</strong>
                                </div>
                                <span class="row-value"><?= htmlspecialchars((string)$ref['nbPhase']) ?></span>
                            </div>
                            <?php endif; ?>
                            <div class="card-row">
                                <div class="row-label-container">
                                    <i data-lucide="building" aria-hidden="true"></i>
                                    <strong>Nombre d'entreprises</strong>
                                </div>
                                <span class="row-value"><?= !empty($ref['nbE']) ? htmlspecialchars((string)$ref['nbE']) : '—' ?></span>
                            </div>
                            <div class="card-row">
                                <div class="row-label-container">
                                    <i data-lucide="boxes" aria-hidden="true"></i>
                                    <strong>Nombre de lots</strong>
                                </div>
                                <span class="row-value"><?= !empty($ref['nombre_lots']) ? htmlspecialchars((string)$ref['nombre_lots']) : '—' ?></span>
                            </div>
                        </div>

                        <!-- Card Footer Section with 3 columns (Period, Status, Montant) -->
                        <div class="card-footer">
                            <!-- Period Column -->
                            <div class="footer-col footer-col--periode">
                                <i data-lucide="calendar" class="footer-icon" aria-hidden="true"></i>
                                <div class="footer-text-group">
                                    <span class="footer-sub-label">Période</span>
                                    <?php if (!empty($ref['anneeD'])): ?>
                                        <span class="footer-val"><?= htmlspecialchars($ref['anneeD']) ?><?= !empty($ref['anneeF']) ? ' - ' . htmlspecialchars($ref['anneeF']) : '' ?></span>
                                        <?php if (!empty($ref['duree_travaux_mois'])): ?>
                                            <span class="footer-sub">(<?= htmlspecialchars($ref['duree_travaux_mois']) ?> mois)</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="footer-val">—</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Status Column -->
                            <div class="footer-col footer-col--statut">
                                <i data-lucide="check-circle" class="footer-icon" aria-hidden="true"></i>
                                <div class="footer-text-group">
                                    <span class="footer-sub-label">Statut</span>
                                    <span class="footer-val"><?= !empty($ref['statut']) ? htmlspecialchars(statutLabel($ref['statut'])) : '—' ?></span>
                                </div>
                            </div>

                            <!-- Montant Column -->
                            <div class="footer-col footer-col--montant">
                                <i data-lucide="euro" class="footer-icon" aria-hidden="true"></i>
                                <div class="footer-text-group">
                                    <span class="footer-sub-label">Montant des travaux</span>
                                    <?php if (!empty($ref['montant'])): ?>
                                        <span class="footer-val"><?= htmlspecialchars(formatNumber($ref['montant'])) ?> € HT</span>
                                    <?php else: ?>
                                        <span class="footer-val">—</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </aside>
                </section>
            </div>
        </div>

        <?php if (!empty($sameDomainRefs)): ?>
            <section class="same-domain-section">
                <div class="site-container">
                    <h2>Références du même domaine</h2>
                    <div class="same-domain-grid">
                        <?php foreach($sameDomainRefs as $sameRef): ?>
                            <?php $img = $samePicByRef[$sameRef['id']] ?? null; ?>
                            <article class="same-domain-card">
                                <a href="/reference/<?= htmlspecialchars($sameRef['id']) ?>" aria-label="<?= htmlspecialchars($sameRef['titre']) ?>"></a>
                                <?php if ($img): ?>
                                    <img src="/pic/card/<?= htmlspecialchars($img) ?>" alt="Projet similaire - <?= htmlspecialchars($sameRef['titre']) ?> à <?= htmlspecialchars($sameRef['commune'] ?? '') ?> par APSI BTP" loading="lazy">
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
    window.addEventListener('load', function() {
        if (window.lucide) window.lucide.createIcons();
    });

    document.addEventListener('DOMContentLoaded', function() {
        const image = document.getElementById('main-reference-image');
        const thumbs = Array.from(document.querySelectorAll('.reference-thumbs button'));
        let current = 0;

        function show(index) {
            if (!image || thumbs.length === 0) return;
            current = (index + thumbs.length) % thumbs.length;
            thumbs.forEach((thumb, thumbIndex) => thumb.classList.toggle('active', thumbIndex === current));
            image.src = thumbs[current].dataset.src;

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
