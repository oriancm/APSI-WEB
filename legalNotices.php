<?php $activePage = ''; ?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mentions Légales - APSI BTP</title>
    <meta name="description" content="Consultez les mentions légales de la société APSI BTP, éditeur du site et coordinateur d'ordonnancement de chantier.">
    <meta name="keywords" content="mentions légales, APSI BTP, informations légales, éditeur du site">
    <meta name="author" content="APSI BTP">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://apsi-btp.fr/legalNotices">
    
    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://apsi-btp.fr/legalNotices">
    <meta property="og:title" content="Mentions Légales - APSI BTP">
    <meta property="og:description" content="Consultez les mentions légales de la société APSI BTP, éditeur du site et coordinateur d'ordonnancement de chantier.">
    <meta property="og:image" content="https://apsi-btp.fr/img/APSI.avif">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Mentions Légales - APSI BTP">
    <meta name="twitter:description" content="Consultez les mentions légales de la société APSI BTP, éditeur du site.">
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
        <?= apsi_minify_css(__DIR__ . '/css/hero-layout-test.css'); ?>
        <?= apsi_minify_css(__DIR__ . '/css/legalNotices.css'); ?>
    </style>
    
    <!-- Fonts Preload -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap">
    </noscript>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>
    <script src="/js/navigation.js"></script>
    <script src="/js/site.js" defer></script>
</head>
<body class="legal-page-body">
    <main class="legal-page site-shell-bg">
        <?php include __DIR__ . '/partials/siteHeader.php'; ?>

        <section class="feature-hero legal-intro" aria-labelledby="legal-title">
            <div class="feature-hero__inner">
                <div class="feature-hero__copy">
                    <p class="feature-hero__eyebrow">Informations légales</p>
                    <h1 id="legal-title">Mentions <span>légales</span></h1>
                    <p class="feature-hero__description">Retrouvez ci-dessous les informations légales relatives au site d’APSI BTP.</p>
                </div>
            </div>
        </section>

        <section class="legal-panel" aria-label="Éditeur et hébergement du site">
                <article class="legal-card">
                    <div class="legal-card__heading">
                    <div class="legal-icon"><i data-lucide="file-text" aria-hidden="true"></i></div>
                    <h2>Éditeur du site</h2>
                    </div>
                    <dl>
                        <div><dt>Société</dt><dd><strong>APSI BTP</strong>, société de type <strong>SARL</strong>, au capital de <strong>6 800,00 €</strong></dd></div>
                        <div><dt>Siège social</dt><dd>Quartier Pierrefeu, chemin de Saint Jean, 84750 CASENEUVE</dd></div>
                        <div><dt>RCS</dt><dd>Avignon : <strong>2007 B 498</strong></dd></div>
                        <div><dt>Numéro de TVA</dt><dd><strong>FR 40 495 392 557</strong></dd></div>
                        <div><dt>Directeur de la publication</dt><dd><strong>Ludovic MESSY</strong></dd></div>
                        <div><dt>Contact</dt><dd><a href="mailto:info@apsi-btp.fr"><strong>info@apsi-btp.fr</strong></a></dd></div>
                    </dl>
                </article>

                <article class="legal-card">
                    <div class="legal-card__heading">
                    <div class="legal-icon"><i data-lucide="server" aria-hidden="true"></i></div>
                    <h2>Hébergement</h2>
                    </div>
                    <dl>
                        <div><dt>Hébergeur</dt><dd><strong>OVH</strong></dd></div>
                        <div><dt>Adresse</dt><dd>2 rue Kellermann,<br>59100 Roubaix, France</dd></div>
                        <div><dt>Site web</dt><dd><a href="https://www.ovh.com" target="_blank" rel="noopener">www.ovh.com</a></dd></div>
                        <div><dt>Téléphone</dt><dd>1007 <small>(gratuit depuis un poste fixe en France)</small></dd></div>
                    </dl>
                </article>
        </section>
    </main>

    <?php include __DIR__ . '/partials/siteFooter.php'; ?>

    <script>
        window.apsiPageReady(function () {
            if (window.lucide) window.lucide.createIcons();
        });
    </script>
</body>
</html>
