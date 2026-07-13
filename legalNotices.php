<?php $activePage = 'contact'; ?>
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

    <!-- Fonts Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <!-- Stylesheets (Inlined dynamically via PHP to prevent FOUC & maximize mobile performance) -->
    <style>
        <?php include __DIR__ . '/css/site.css'; ?>
        <?php include __DIR__ . '/css/legalNotices.css'; ?>
    </style>
    
    <!-- Fonts Preload -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap">
    </noscript>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>
    <script src="/js/site.js" defer></script>
</head>
<body class="legal-page-body">
    <main class="legal-page site-shell-bg">
        <?php include __DIR__ . '/partials/siteHeader.php'; ?>

        <section class="legal-hero site-container">
            <h1 class="page-title">Mentions légales</h1>
            <span class="page-line"></span>

            <div class="legal-panel">
                <article class="legal-row">
                    <div class="legal-icon"><i data-lucide="file-text" aria-hidden="true"></i></div>
                    <div class="legal-content">
                        <h2>Éditeur du site</h2>
                        <span class="small-line"></span>
                        <p>Le présent site est édité par <strong>APSI BTP</strong>, société de type <strong>SARL</strong>, au capital de <strong>6 800,00 €</strong></p>
                        <ul>
                            <li>Siège social : Quartier Pierrefeu, chemin de Saint Jean, 84750 CASENEUVE</li>
                            <li>RCS Avignon : <strong>2007 B 498</strong></li>
                            <li>Numéro de TVA : <strong>FR 40 495 392 557</strong></li>
                            <li>Directeur de la publication : <strong>Ludovic MESSY</strong></li>
                            <li>Contact : <strong>test@apsi-btp.fr</strong></li>
                        </ul>
                    </div>
                </article>

                <article class="legal-row">
                    <div class="legal-icon"><i data-lucide="server" aria-hidden="true"></i></div>
                    <div class="legal-content">
                        <h2>Hébergement</h2>
                        <span class="small-line"></span>
                        <p>Le site est hébergé par : <strong>OVH</strong></p>
                        <ul>
                            <li>Adresse : 2 rue Kellermann, 59100 Roubaix, France</li>
                            <li>Site web : <a href="https://www.ovh.com" target="_blank" rel="noopener"><strong>https://www.ovh.com</strong></a></li>
                            <li>Téléphone : <strong>1007</strong> (gratuit depuis un poste fixe en France)</li>
                        </ul>
                    </div>
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
