<?php $activePage = 'contact'; ?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Politique de Confidentialité - APSI BTP</title>
    <meta name="description" content="Consultez la politique de confidentialité d'APSI BTP concernant l'usage du formulaire de contact et la protection de vos données.">
    <meta name="keywords" content="politique de confidentialité, APSI BTP, protection des données, rgpd">
    <meta name="author" content="APSI BTP">
    <meta name="robots" content="index, follow">
    <link class="canonical" rel="canonical" href="https://apsi-btp.fr/privacyPolicy">
    
    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://apsi-btp.fr/privacyPolicy">
    <meta property="og:title" content="Politique de Confidentialité - APSI BTP">
    <meta property="og:description" content="Consultez la politique de confidentialité d'APSI BTP concernant l'usage du formulaire de contact et la protection de vos données.">
    <meta property="og:image" content="https://apsi-btp.fr/img/APSI.avif">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Politique de Confidentialité - APSI BTP">
    <meta name="twitter:description" content="Consultez la politique de confidentialité d'APSI BTP concernant la protection de vos données.">
    <meta name="twitter:image" content="https://apsi-btp.fr/img/APSI.avif">

    <!-- Favicons & Manifest -->
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
        <?= apsi_minify_css(__DIR__ . '/css/policyPrivacy.css'); ?>
    </style>
    
    <!-- Fonts Preload -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap">
    </noscript>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>
    <script src="/js/site.js" defer></script>
</head>
<body class="privacy-page-body">
    <main class="privacy-page site-shell-bg">
        <?php include __DIR__ . '/partials/siteHeader.php'; ?>

        <section class="privacy-hero site-container">
            <h1 class="page-title">Politique de confidentialité</h1>
            <span class="page-line"></span>

            <div class="privacy-panel">
                <article>
                    <div class="privacy-icon"><i data-lucide="shield-check" aria-hidden="true"></i></div>
                    <p>Ce site ne collecte <strong>pas de données personnelles</strong> en dehors de celles fournies via le formulaire de contact.</p>
                </article>
                <article>
                    <div class="privacy-icon"><i data-lucide="file-pen-line" aria-hidden="true"></i></div>
                    <p>Le formulaire de contact présent sur ce site recueille certaines informations (nom, objet, message) <strong>uniquement dans le but de permettre à l'entreprise de répondre aux demandes envoyées.</strong></p>
                </article>
                <article>
                    <div class="privacy-icon"><i data-lucide="database-x" aria-hidden="true"></i></div>
                    <p>Ces données ne sont <strong>ni stockées</strong> en base de données, <strong>ni utilisées</strong> à des fins commerciales ou publicitaires, <strong>ni transmises</strong> à des tiers.</p>
                </article>
                <article>
                    <div class="privacy-icon"><i data-lucide="mail" aria-hidden="true"></i></div>
                    <p>Si vous avez des questions concernant le traitement de vos données, vous pouvez contacter :<br><strong>test@apsi-btp.fr</strong></p>
                </article>
            </div>
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
