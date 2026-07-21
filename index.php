<?php $activePage = 'home'; ?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>APSI BTP - Vos projets en toute sérénité | Ordonnancement Pilotage Coordination</title>
    <meta name="description" content="APSI BTP, spécialiste en Ordonnancement Pilotage et Coordination (OPC) et Maîtrise d'Oeuvre d'Exécution (MOEX) depuis 2007.">
    <meta name="keywords" content="APSI BTP, OPC, ordonnancement pilotage coordination, maîtrise d'oeuvre, construction, réhabilitation, chantier, Provence, PACA">
    <meta name="author" content="APSI BTP">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://apsi-btp.fr/">
    
    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://apsi-btp.fr/">
    <meta property="og:title" content="APSI BTP - Vos projets en toute sérénité | Ordonnancement Pilotage Coordination">
    <meta property="og:description" content="APSI BTP, spécialiste en Ordonnancement Pilotage et Coordination (OPC) et Maîtrise d'Oeuvre d'Exécution (MOEX) depuis 2007.">
    <meta property="og:image" content="https://apsi-btp.fr/img/APSI.avif">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="APSI BTP - Vos projets en toute sérénité">
    <meta name="twitter:description" content="Spécialiste en Ordonnancement Pilotage Coordination (OPC) et Maîtrise d'Oeuvre d'Exécution (MOEX) depuis 2007.">
    <meta name="twitter:image" content="https://apsi-btp.fr/img/APSI.avif">

    <!-- Schema.org JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "name": "APSI BTP",
      "alternateName": "Ordonnancement Pilotage Coordination BTP",
      "url": "https://apsi-btp.fr/",
      "logo": "https://apsi-btp.fr/img/APSI.avif",
      "image": "https://apsi-btp.fr/img/APSI.avif",
      "description": "APSI BTP, spécialiste en Ordonnancement Pilotage et Coordination (OPC) et Maîtrise d'Oeuvre d'Exécution (MOEX) depuis 2007 en région Provence-Alpes-Côte d'Azur.",
      "foundingDate": "2007",
      "priceRange": "$$",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Quartier Pierrefeu, chemin de Saint Jean",
        "addressLocality": "Caseneuve",
        "addressRegion": "Provence-Alpes-Côte d'Azur",
        "postalCode": "84750",
        "addressCountry": "FR"
      },
      "contactPoint": {
        "@type": "ContactPoint",
        "email": "test@apsi-btp.fr",
        "contactType": "customer service"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": "43.8876",
        "longitude": "5.4344"
      }
    }
    </script>

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
        <?= apsi_minify_css(__DIR__ . '/css/style.css'); ?>
    </style>
    <!-- Fonts Preload -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800;900&family=Inter:wght@400;500;600;700;800&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800;900&family=Inter:wght@400;500;600;700;800&display=swap">
    </noscript>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>
    <script src="/js/site.js" defer></script>
</head>
<body class="home-page">
    <main class="home-shell">
        <?php include __DIR__ . '/partials/siteHeader.php'; ?>

        <section class="home-hero">
            <div class="home-hero__copy">
                <h1>De tous les actes,<br>le plus complet<br>est celui de construire.</h1>
                <p>Paul Valéry</p>
                <a href="/references" class="home-reference-button">
                    <span>Découvrir nos références</span>
                    <i data-lucide="arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        </section>

        <section class="home-service-cards" aria-label="Domaines d'intervention">
            <article>
                <i data-lucide="users" aria-hidden="true"></i>
                <h2>Coordination de chantier</h2>
                <p>Organisation des interventions et coordination des entreprises.</p>
            </article>
            <article>
                <i data-lucide="calendar" aria-hidden="true"></i>
                <h2>Planification des travaux</h2>
                <p>Suivi des délais, phasage et anticipation des contraintes d’exécution.</p>
            </article>
            <article>
                <i data-lucide="hard-hat" aria-hidden="true"></i>
                <h2>Suivi opérationnel</h2>
                <p>Présence terrain, dialogue constant et suivi du chantier jusqu’à la livraison.</p>
            </article>
        </section>
    </main>

    <?php include __DIR__ . '/partials/siteFooter.php'; ?>

    <script>
        window.addEventListener('load', function () {
            if (window.lucide) {
                window.lucide.createIcons();
            }
        });
    </script>
</body>
</html>
