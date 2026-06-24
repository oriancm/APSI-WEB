<?php
require('admin/db.php');

$id = $_GET['id'] ?? null;

function fallbackReferences(): array {
    return [
        ['id' => 10001, 'titre' => 'Groupe scolaire en site occupé', 'commune' => 'Avignon', 'domaine' => '4', 'statut' => '1', 'anneeD' => '2021', 'anneeF' => '2023', 'duree_travaux_mois' => '20', 'moa' => "Ville d'Avignon", 'archi' => 'Atelier Provence Architecture', 'eMoe' => 'Méditerranée Structure', 'montant' => 3200000, 'nbPhase' => 5, 'nbE' => 12, 'nombre_lots' => 10, 'description' => "Réhabilitation d'un groupe scolaire en site occupé avec phasage précis des interventions et maintien de l'activité."],
        ['id' => 10002, 'titre' => 'Réhabilitation de logements collectifs', 'commune' => 'Apt', 'domaine' => '10', 'statut' => '1', 'anneeD' => '2020', 'anneeF' => '2022', 'duree_travaux_mois' => '18', 'moa' => 'Bailleur social Provence Habitat', 'archi' => 'Studio Ligne Claire', 'eMoe' => 'Méditerranée Structure', 'montant' => 2800000, 'nbPhase' => 4, 'nbE' => 10, 'nombre_lots' => 9, 'description' => 'Réhabilitation thermique et fonctionnelle de logements collectifs avec coordination des interventions en site occupé.'],
        ['id' => 10003, 'titre' => 'Pôle santé et services publics', 'commune' => 'Manosque', 'domaine' => '9', 'statut' => '1', 'anneeD' => '2021', 'anneeF' => '2023', 'duree_travaux_mois' => '16', 'moa' => "Communauté d'agglomération", 'archi' => 'Atelier Santé', 'eMoe' => 'BET Provence', 'montant' => 3600000, 'nbPhase' => 3, 'nbE' => 13, 'nombre_lots' => 11, 'description' => "Création d'un équipement de santé et de services publics avec coordination renforcée des lots techniques."],
        ['id' => 10004, 'titre' => 'Gymnase intercommunal du Vallon', 'commune' => 'Pertuis', 'domaine' => '2', 'statut' => '1', 'anneeD' => '2020', 'anneeF' => '2022', 'duree_travaux_mois' => '18', 'moa' => 'Communauté Territoriale Sud Luberon', 'archi' => 'Studio Ligne Claire', 'eMoe' => 'Méditerranée Structure', 'montant' => 4200000, 'nbPhase' => 4, 'nbE' => 14, 'nombre_lots' => 12, 'description' => "Réalisation d'un gymnase multisports comprenant tribunes, vestiaires, dojo annexe et plateau extérieur. Coordination renforcée des lots charpente bois, sols sportifs et traitement acoustique."],
        ['id' => 10005, 'titre' => 'Équipement culturel et médiathèque', 'commune' => 'Cavaillon', 'domaine' => '3', 'statut' => '1', 'anneeD' => '2022', 'anneeF' => '2024', 'duree_travaux_mois' => '19', 'moa' => 'Ville de Cavaillon', 'archi' => 'Agence Culture & Bois', 'eMoe' => 'BET Lumière', 'montant' => 5100000, 'nbPhase' => 4, 'nbE' => 16, 'nombre_lots' => 13, 'description' => "Construction d'un équipement culturel et d'une médiathèque intégrant des espaces publics modulables."],
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
        $localPics = ['img5.jpg', 'img4.jpg', 'img3.jpg', 'img2.jpg', 'img1.jpg'];
        return array_map(fn($pic, $index) => ['id' => $index + 1, 'idR' => $idR, 'titre' => $pic, 'orderPic' => $index + 1], $localPics, array_keys($localPics));
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
        $fallback = [];
        $localPics = ['img1.jpg', 'img2.jpg', 'img3.jpg', 'img4.jpg', 'img5.jpg'];
        foreach (fallbackReferences() as $index => $ref) {
            $fallback[] = ['idR' => $ref['id'], 'titre' => $localPics[$index % count($localPics)], 'orderPic' => 1];
        }
        return $fallback;
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
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
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
                            
                            <img id="main-reference-image" src="/pic/<?= htmlspecialchars($mainPic) ?>" alt="">

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
                                    <img src="/pic/<?= htmlspecialchars($pic['titre']) ?>" alt="">
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
                            <div class="card-row">
                                <div class="row-label-container">
                                    <i data-lucide="pencil" aria-hidden="true"></i>
                                    <strong>Architecte</strong>
                                </div>
                                <span class="row-value"><?= htmlspecialchars($ref['archi'] ?? '—') ?></span>
                            </div>
                            <div class="card-row">
                                <div class="row-label-container">
                                    <i data-lucide="users" aria-hidden="true"></i>
                                    <strong>Équipe de Maîtrise d'Œuvre</strong>
                                </div>
                                <span class="row-value"><?= htmlspecialchars($ref['eMoe'] ?? '—') ?></span>
                            </div>
                            <div class="card-row">
                                <div class="row-label-container">
                                    <i data-lucide="git-branch" aria-hidden="true"></i>
                                    <strong>Nombre de phases</strong>
                                </div>
                                <span class="row-value"><?= !empty($ref['nbPhase']) ? htmlspecialchars((string)$ref['nbPhase']) : '—' ?></span>
                            </div>
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
                                    <span class="footer-val"><?= !empty($ref['statut']) ? htmlspecialchars(statutLabel($ref['statut'])) : '—' ?></span>
                                </div>
                            </div>

                            <!-- Montant Column -->
                            <div class="footer-col footer-col--montant">
                                <div class="euro-badge">€</div>
                                <div class="footer-text-group">
                                    <span class="footer-sub-label">Montant des travaux</span>
                                    <?php if (!empty($ref['montant'])): ?>
                                        <span class="footer-val-large"><?= htmlspecialchars(formatNumber($ref['montant'])) ?> € HT</span>
                                    <?php else: ?>
                                        <span class="footer-val-large">—</span>
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
                                    <img src="/pic/<?= htmlspecialchars($img) ?>" alt="">
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
