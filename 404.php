<?php
http_response_code(404);
require_once __DIR__ . '/functions/assets.php';
$activePage = '';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, follow">
    <meta name="description" content="La page demandée est introuvable. Retrouvez les métiers, les références et les coordonnées d’APSI BTP.">
    <title>Page introuvable | APSI BTP</title>
    <?php require __DIR__ . '/partials/favicons.php'; ?>
    <link data-apsi-style rel="stylesheet" href="<?= apsiAsset('/css/site.css') ?>">
    <link rel="stylesheet" href="<?= apsiAsset('/css/fonts.css') ?>">
    <script src="<?= apsiAsset('/js/icons.min.js') ?>" defer></script>
    <script src="<?= apsiAsset('/js/navigation.js') ?>"></script>
    <script src="<?= apsiAsset('/js/site.js') ?>" defer></script>
</head>
<body>
    <a class="skip-link" href="#main">Aller au contenu principal</a>
    <?php include __DIR__ . '/partials/siteHeader.php'; ?>
    <main id="main" tabindex="-1" class="site-container" style="padding-block: 80px; min-height: 55vh">
        <h1>Page introuvable</h1>
        <p>Cette page n’existe pas ou a été déplacée.</p>
        <p><a href="/">Retour à l’accueil</a> · <a href="/references">Découvrir nos références</a></p>
    </main>
    <?php include __DIR__ . '/partials/siteFooter.php'; ?>
</body>
</html>
