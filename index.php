<?php $activePage = 'home'; ?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>APSI BTP - Ordonnancement Pilotage Coordination</title>
    <meta name="description" content="APSI BTP, spécialiste en Ordonnancement Pilotage et Coordination (OPC) et Maîtrise d'Oeuvre d'Exécution (MOEX) depuis 2007.">
    <meta name="keywords" content="APSI BTP, OPC, ordonnancement pilotage coordination, maîtrise d'oeuvre, construction, réhabilitation, chantier, Provence, PACA">
    <meta name="author" content="APSI BTP">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://apsi-btp.fr/">
    
    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://apsi-btp.fr/">
    <meta property="og:title" content="APSI BTP - Ordonnancement Pilotage Coordination">
    <meta property="og:description" content="APSI BTP, spécialiste en Ordonnancement Pilotage et Coordination (OPC) et Maîtrise d'Oeuvre d'Exécution (MOEX) depuis 2007.">
    <meta property="og:image" content="https://apsi-btp.fr/img/APSI.avif">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="APSI BTP - Ordonnancement Pilotage Coordination">
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

    <section class="home-intro-section" aria-label="Présentation de l'entreprise">
        <div class="home-intro__copy">
            <h2>Votre Partenaire de Confiance en Pilotage et Coordination de Chantiers</h2>
            <p>Depuis sa création en 2007, <strong>APSI BTP</strong> accompagne les maîtres d'ouvrage publics et privés dans la réussite de leurs projets de construction, de réhabilitation et d'aménagement. Spécialistes reconnus en <strong>Ordonnancement, Pilotage et Coordination (OPC)</strong> ainsi qu'en <strong>Maîtrise d'Œuvre d'Exécution (MOEX)</strong>, nous intervenons principalement en région Provence-Alpes-Côte d'Azur (PACA).</p>
            <p>Notre mission est de garantir le respect rigoureux des délais, la maîtrise des coûts et une qualité d'exécution irréprochable sur chaque chantier. Grâce à une présence constante sur le terrain et un dialogue permanent avec toutes les entreprises intervenantes, nous anticipons les risques opérationnels et résolvons les complexités logistiques avant qu'elles ne ralentissent vos travaux.</p>
        </div>
        <div class="home-intro__features">
            <h3>Pourquoi faire appel à APSI BTP ?</h3>
            <ul>
                <li><strong>Expertise et savoir-faire éprouvés</strong> : Plus de 100 opérations d'envergure menées à bien, de la construction de logements neufs à la réhabilitation technique en site occupé.</li>
                <li><strong>Rigueur et réactivité</strong> : Une gestion de projet proactive s'appuyant sur des méthodes de planification avancées pour anticiper chaque étape clé.</li>
                <li><strong>Ancrage local et proximité</strong> : Basés à Caseneuve (84750), nous intervenons efficacement dans le Vaucluse, les Bouches-du-Rhône et les départements limitrophes pour un suivi terrain au plus près de vos besoins.</li>
            </ul>
        </div>
    </section>

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
