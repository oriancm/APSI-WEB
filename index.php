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
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://apsi-btp.fr/">
    <meta property="og:title" content="APSI BTP - Vos projets en toute sérénité">
    <meta property="og:description" content="Spécialiste en Ordonnancement Pilotage Coordination (OPC) et Maîtrise d'Oeuvre d'Exécution (MOEX) depuis 2007.">
    <meta property="og:image" content="/img/APSI.png">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="stylesheet" href="/css/site.css?v=<?= time() ?>">
    <link rel="stylesheet" href="/css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800;900&family=Inter:wght@400;500;600;700;800&display=swap">
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
