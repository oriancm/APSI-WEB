<?php $activePage = 'clients'; ?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nos Clients - APSI BTP | Partenaires et Maîtres d'Ouvrage</title>
    <meta name="description" content="Découvrez nos clients et partenaires : collectivités, institutions publiques et privées qui nous font confiance pour leurs projets BTP.">
    <meta name="keywords" content="clients APSI BTP, partenaires, maîtres d'ouvrage, collectivités, institutions, Provence, PACA">
    <meta name="author" content="APSI BTP">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://apsi-btp.fr/clients">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://apsi-btp.fr/clients">
    <meta property="og:title" content="Nos Clients - APSI BTP">
    <meta property="og:description" content="Découvrez nos clients et partenaires qui nous font confiance pour leurs projets BTP.">
    <meta property="og:image" content="https://apsi-btp.fr/img/APSI.avif">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Nos Clients - APSI BTP">
    <meta name="twitter:description" content="Découvrez nos clients et partenaires qui nous font confiance pour leurs projets BTP.">
    <meta name="twitter:image" content="https://apsi-btp.fr/img/APSI.avif">

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
        <?= apsi_minify_css(__DIR__ . '/css/clients.css'); ?>
    </style>
    
    <!-- Fonts Preload -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap">
    </noscript>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>
    <script src="/js/site.js" defer></script>
</head>
<body class="clients-page">
    <main class="clients-shell site-shell-bg">
        <?php include __DIR__ . '/partials/siteHeader.php'; ?>

        <section class="clients-hero site-container">
            <h1 class="page-title">Nos Clients</h1>
            <span class="page-line"></span>
            <p>APSI BTP accompagne depuis plus de 15 ans des maîtres d'ouvrage publics et privés dans la réussite de leurs projets de <strong>construction</strong> et de <strong>réhabilitation.</strong></p>
        </section>

        <section class="clients-grid site-container" aria-label="Logos clients">
            <article><img src="/img/logo/alpes.png" alt="Alpes de Haute Provence"></article>
            <article><a href="https://www.chateauneuflesmartigues.fr/" target="_blank" rel="noopener"><img src="/img/logo/chateauneuf.png" alt="Châteauneuf-les-Martigues"></a></article>
            <article><a href="https://citadis.fr/" target="_blank" rel="noopener"><img src="/img/logo/citadis.png" alt="Citadis"></a></article>
            <article><a href="https://www.coudoux.fr/" target="_blank" rel="noopener"><img src="/img/logo/coudoux.png" alt="Coudoux"></a></article>
            <article><a href="https://www.granddelta.fr/" target="_blank" rel="noopener"><img src="/img/logo/GrandDelta.png" alt="Grand Delta Habitat"></a></article>
            <article><a href="https://www.sdis84.fr/" target="_blank" rel="noopener"><img src="/img/logo/vaucluseSapeurs.png" alt="Sapeurs-pompiers du Vaucluse"></a></article>
            <article><a href="https://www.justice.gouv.fr/" target="_blank" rel="noopener"><img src="/img/logo/justice.png" alt="Ministère de la Justice"></a></article>
            <article><a href="https://www.lescrous.fr/" target="_blank" rel="noopener"><img src="/img/logo/crous.png" alt="Les Crous"></a></article>
            <article><img src="/img/logo/Logo_ville_Vitrolles.png" alt="Ville de Vitrolles"></article>
            <article><a href="https://www.onf.fr/" target="_blank" rel="noopener"><img src="/img/logo/onf.png" alt="Office National des Forêts"></a></article>
            <article><a href="https://www.paysapt-luberon.fr/" target="_blank" rel="noopener"><img src="/img/logo/paysdapt.jpg" alt="Pays d'Apt Luberon"></a></article>
            <article><a href="https://www.provencealpesagglo.fr/" target="_blank" rel="noopener"><img src="/img/logo/provenceAlpes.png" alt="Provence Alpes Agglo"></a></article>
            <article><a href="https://www.sdis04.fr/" target="_blank" rel="noopener"><img src="/img/logo/sdis.png" alt="SDIS Alpes de Haute-Provence"></a></article>
            <article><a href="https://www.soleam.net/" target="_blank" rel="noopener"><img src="/img/logo/soleam.png" alt="Soleam"></a></article>
            <article><img src="/img/logo/talpesdusud.svg" alt="Groupement Hospitalier de Territoire Alpes du Sud"></article>
            <article><a href="https://splterritoire84.com/fr" target="_blank" rel="noopener"><img src="/img/logo/territoire.png" alt="Territoire 84"></a></article>
            <article><a href="https://var.fr/" target="_blank" rel="noopener"><img src="/img/logo/var.svg.png" alt="Var Le Département"></a></article>
            <article><a href="https://www.vaucluse.fr/accueil-3.html" target="_blank" rel="noopener"><img src="/img/logo/vaucluse.svg.png" alt="Vaucluse Le Département"></a></article>
            <article><a href="https://www.apt.fr/" target="_blank" rel="noopener"><img src="/img/logo/ville.png" alt="Ville d'Apt"></a></article>
        </section>
    </main>

    <?php include __DIR__ . '/partials/siteFooter.php'; ?>

    <script>
        window.addEventListener('load', function () {
            if (window.lucide) window.lucide.createIcons();
        });
    </script>
</body>
</html>
