<?php $activePage = 'home'; ?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>APSI BTP | OPC et MOEX en Provence depuis 2007</title>
    <meta name="description" content="APSI BTP, spécialiste en Ordonnancement Pilotage et Coordination (OPC) et Maîtrise d'Oeuvre d'Exécution (MOEX) depuis 2007.">
    <meta name="keywords" content="APSI BTP, OPC, ordonnancement pilotage coordination, maîtrise d'oeuvre, construction, réhabilitation, chantier, Provence, PACA">
    <meta name="author" content="APSI BTP">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://apsi-btp.fr/">
    
    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://apsi-btp.fr/">
    <meta property="og:title" content="APSI BTP | OPC et MOEX en Provence depuis 2007">
    <meta property="og:description" content="APSI BTP, spécialiste en Ordonnancement Pilotage et Coordination (OPC) et Maîtrise d'Oeuvre d'Exécution (MOEX) depuis 2007.">
    <meta property="og:image" content="https://apsi-btp.fr/img/logo-nav.webp">
    <meta property="og:image:type" content="image/webp">
    <meta property="og:image:width" content="1146">
    <meta property="og:image:height" content="652">
    <meta property="og:image:alt" content="APSI BTP - Coordination de travaux">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="APSI BTP - Vos projets en toute sérénité">
    <meta name="twitter:description" content="Spécialiste en Ordonnancement Pilotage Coordination (OPC) et Maîtrise d'Oeuvre d'Exécution (MOEX) depuis 2007.">
    <meta name="twitter:image" content="https://apsi-btp.fr/img/logo-nav.webp">
    <meta name="twitter:image:alt" content="APSI BTP - Coordination de travaux">

    <!-- Schema.org JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "name": "APSI BTP",
      "alternateName": "Ordonnancement Pilotage Coordination BTP",
      "url": "https://apsi-btp.fr/",
      "logo": "https://apsi-btp.fr/img/logo-nav.webp",
      "image": "https://apsi-btp.fr/img/logo-nav.webp",
      "description": "APSI BTP, spécialiste en Ordonnancement Pilotage et Coordination (OPC) et Maîtrise d'Oeuvre d'Exécution (MOEX) depuis 2007 en région Provence-Alpes-Côte d'Azur.",
      "foundingDate": "2007",
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
        "email": "info@apsi-btp.fr",
        "contactType": "customer service"
      }
    }
    </script>

    <!-- Favicons & Manifest -->
    <?php require __DIR__ . '/partials/favicons.php'; ?>
    <link rel="preload" as="image" href="/img/mobile/home-crane-portrait.webp" media="(max-width: 980px)" fetchpriority="high">
    <link rel="preload" as="image" href="/img/home-crane-optimized.webp" media="(min-width: 981px)">
    <!-- Fonts Preconnect -->

        <!-- Stylesheets (Inlined dynamically via PHP to prevent FOUC & maximize mobile performance) -->
        <?php require_once __DIR__ . '/functions/assets.php'; ?>
    <link data-apsi-style rel="stylesheet" href="<?= apsiAsset('/css/site.css') ?>">
    <link data-apsi-style rel="stylesheet" href="<?= apsiAsset('/css/style.css') ?>">
    <link data-apsi-style rel="stylesheet" href="<?= apsiAsset('/css/hero-layout-test.css') ?>">
    <!-- Fonts Preload -->
    <link rel="stylesheet" href="<?= apsiAsset('/css/fonts.css') ?>">
    <script src="<?= apsiAsset('/js/icons.min.js') ?>" defer></script>
    <script src="<?= apsiAsset('/js/navigation.js') ?>"></script>
    <script src="<?= apsiAsset('/js/site.js') ?>" defer></script>
    <?php include __DIR__ . '/partials/seo.php'; ?>
</head>
<body class="home-page">
    <a class="skip-link" href="#main">Aller au contenu principal</a>
    <main id="main" tabindex="-1" class="home-shell">
        <div class="home-scene" aria-hidden="true">
            <?php include __DIR__ . '/partials/heroFrame.php'; ?>
        </div>
        <?php include __DIR__ . '/partials/siteHeader.php'; ?>

        <section class="home-hero">
            <div class="home-hero__copy">
                <h1><span class="sr-only">APSI BTP — Coordination de chantier OPC et MOEX en Provence. </span>De tous les actes,<br>le plus complet<br>est celui de<br class="home-slogan-mobile-break"> construire.</h1>
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
        window.apsiPageReady(function () {
            if (window.lucide) {
                window.lucide.createIcons();
            }
        });
    </script>
</body>
</html>
