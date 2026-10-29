<?php $activePage = 'privacy'; ?>
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
    <meta property="og:image" content="https://apsi-btp.fr/img/logo-nav.webp">
    <meta property="og:image:type" content="image/webp">
    <meta property="og:image:width" content="1146">
    <meta property="og:image:height" content="652">
    <meta property="og:image:alt" content="APSI BTP - Coordination de travaux">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Politique de Confidentialité - APSI BTP">
    <meta name="twitter:description" content="Consultez la politique de confidentialité d'APSI BTP concernant la protection de vos données.">
    <meta name="twitter:image" content="https://apsi-btp.fr/img/logo-nav.webp">
    <meta name="twitter:image:alt" content="APSI BTP - Coordination de travaux">

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

        <!-- Stylesheets (Inlined dynamically via PHP to prevent FOUC & maximize mobile performance) -->
        <?php require_once __DIR__ . '/functions/assets.php'; ?>
    <link data-apsi-style rel="stylesheet" href="<?= apsiAsset('/css/site.css') ?>">
    <link data-apsi-style rel="stylesheet" href="<?= apsiAsset('/css/hero-layout-test.css') ?>">
    <link data-apsi-style rel="stylesheet" href="<?= apsiAsset('/css/policyPrivacy.css') ?>">
    
    <!-- Fonts Preload -->
    <link rel="stylesheet" href="<?= apsiAsset('/css/fonts.css') ?>">
    <script src="<?= apsiAsset('/js/icons.min.js') ?>" defer></script>
    <script src="<?= apsiAsset('/js/navigation.js') ?>"></script>
    <script src="<?= apsiAsset('/js/site.js') ?>" defer></script>
    <?php include __DIR__ . '/partials/seo.php'; ?>
</head>
<body class="privacy-page-body">
    <a class="skip-link" href="#main">Aller au contenu principal</a>
    <main id="main" tabindex="-1" class="privacy-page site-shell-bg">
        <?php include __DIR__ . '/partials/siteHeader.php'; ?>

        <section class="feature-hero privacy-intro" aria-labelledby="privacy-title">
            <div class="feature-hero__inner">
                <div class="feature-hero__copy">
                    <p class="feature-hero__eyebrow">Vos données</p>
                    <h1 id="privacy-title">Politique de<br><span>confidentialité</span></h1>
                    <p class="feature-hero__description">Ce site ne collecte pas de données personnelles en dehors de celles fournies via le formulaire de contact.</p>
                </div>
            </div>
        </section>

        <section class="privacy-panel" aria-label="Traitement de vos données">
                <article>
                    <div class="privacy-icon"><i data-lucide="shield-check" aria-hidden="true"></i></div>
                    <div class="privacy-content">
                    <h2>Collecte limitée</h2>
                    <p>Ce site ne collecte <strong>pas de données personnelles</strong> en dehors de celles fournies via le formulaire de contact.</p>
                    </div>
                </article>
                <article>
                    <div class="privacy-icon"><i data-lucide="file-pen-line" aria-hidden="true"></i></div>
                    <div class="privacy-content">
                    <h2>Une finalité précise</h2>
                    <p>Le formulaire de contact présent sur ce site recueille certaines informations (nom, objet, message) <strong>uniquement dans le but de permettre à l'entreprise de répondre aux demandes envoyées.</strong></p>
                    </div>
                </article>
                <article>
                    <div class="privacy-icon"><i data-lucide="database-x" aria-hidden="true"></i></div>
                    <div class="privacy-content">
                    <h2>Utilisation des données</h2>
                    <p>Ces données ne sont <strong>ni stockées</strong> en base de données, <strong>ni utilisées</strong> à des fins commerciales ou publicitaires, <strong>ni transmises</strong> à des tiers.</p>
                    </div>
                </article>
                <article>
                    <div class="privacy-icon"><i data-lucide="mail" aria-hidden="true"></i></div>
                    <div class="privacy-content">
                    <h2>Une question ?</h2>
                    <p>Si vous avez des questions concernant le traitement de vos données, vous pouvez contacter :<br><a href="mailto:info@apsi-btp.fr"><strong>info@apsi-btp.fr</strong></a></p>
                    </div>
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
