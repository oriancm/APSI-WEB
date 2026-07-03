<?php $activePage = 'contact'; ?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>APSI BTP - Politique de confidentialité</title>
    <link rel="stylesheet" href="/css/site.css">
    <link rel="stylesheet" href="/css/policyPrivacy.css?v=20260622-3">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap">
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
                    <div class="privacy-icon"><i data-lucide="shield-lock" aria-hidden="true"></i></div>
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
