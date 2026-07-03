<?php $activePage = 'professions'; ?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>APSI BTP - Nos Métiers</title>
    <link rel="stylesheet" href="/css/site.css">
    <link rel="stylesheet" href="/css/professions.css?v=20260623-2">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>
    <script src="/js/site.js" defer></script>
</head>
<body class="professions-page">
    <main id="main" class="professions-shell site-shell-bg hidden-until-loaded">
        <?php include __DIR__ . '/partials/siteHeader.php'; ?>

        <section class="professions-hero">
            <div class="professions-hero-copy">
                <h1>Nos Métiers</h1>
                <p>APSI BTP accompagne les maîtres d'ouvrage publics et privés dans la réussite de leurs projets de <b>construction</b> et de <b>réhabilitation</b>.</p>
                <p>Nous intervenons sur les missions <b>OPC</b> (Ordonnancement, Pilotage et Coordination) et <b>MOEX</b> (Maîtrise d'Œuvre d'Exécution).</p>
            </div>
        </section>

        <section class="profession-card profession-card--opc">
            <div class="profession-card-copy">
                <div class="profession-title-row">
                    <h2>Ordonnancement,<br>Pilotage et Coordination<br><span>(OPC)</span></h2>
                </div>
                <p>Nous organisons, pilotons et coordonnons l'ensemble des intervenants pour garantir le respect des <b>délais</b>, des <b>budgets</b> et de la <b>qualité</b>.</p>
                <div class="profession-points">
                    <div><i data-lucide="calendar-days" aria-hidden="true"></i><span>Planification<br>optimisée</span></div>
                    <div><i data-lucide="workflow" aria-hidden="true"></i><span>Coordination<br>des intervenants</span></div>
                    <div><i data-lucide="clock-3" aria-hidden="true"></i><span>Respect des délais<br>et des objectifs</span></div>
                </div>
            </div>
            <img src="/img/profession-opc.jpg" alt="Deux professionnels analysant des plans sur un chantier">
        </section>

        <section class="profession-card profession-card--moex">
            <img src="/img/profession-moex.jpg" alt="Professionnel APSI BTP sur chantier avec tablette">
            <div class="profession-card-copy">
                <div class="profession-title-row">
                    <h2>Maîtrise d'Œuvre<br>d'Exécution <span>(MOEX)</span></h2>
                </div>
                <p>Nous assurons le <b>suivi</b> et la <b>direction</b> des travaux, dans le respect des plans, des <b>normes</b> et des <b>exigences</b> du maître d'ouvrage.</p>
                <div class="profession-points">
                    <div><i data-lucide="shield-check" aria-hidden="true"></i><span>Qualité<br>et conformité</span></div>
                    <div><i data-lucide="bar-chart-3" aria-hidden="true"></i><span>Suivi rigoureux<br>du chantier</span></div>
                    <div><i data-lucide="users" aria-hidden="true"></i><span>Accompagnement<br>jusqu'à la réception</span></div>
                </div>
            </div>
        </section>

        <section class="profession-assets">
            <h2>Les atouts de notre accompagnement</h2>
            <div class="profession-assets-grid">
                <div><i data-lucide="hard-hat" aria-hidden="true"></i><span>Expertise<br>technique</span></div>
                <div><i data-lucide="timer" aria-hidden="true"></i><span>Organisation<br>et réactivité</span></div>
                <div><i data-lucide="search" aria-hidden="true"></i><span>Anticipation<br>et optimisation</span></div>
                <div><i data-lucide="clipboard-check" aria-hidden="true"></i><span>Suivi rigoureux<br>et reporting</span></div>
                <div><i data-lucide="handshake" aria-hidden="true"></i><span>Engagement<br>et disponibilité</span></div>
            </div>
            <p>Notre objectif : vous garantir des <b>chantiers maîtrisés</b>, livrés dans les meilleures conditions.</p>
        </section>

        <section class="profession-cta">
            <div class="profession-cta-content">
                <i data-lucide="messages-square" aria-hidden="true"></i>
                <div class="profession-cta-text">
                    <h2>Un partenaire de confiance<br>pour vos projets</h2>
                    <p>Du conseil à la livraison, <b>APSI BTP</b><br>vous accompagne à chaque étape.</p>
                </div>
            </div>
            <a href="/contact" class="profession-cta-btn"><span>Nous contacter</span><i data-lucide="arrow-right" aria-hidden="true"></i></a>
        </section>

    </main>

    <?php include __DIR__ . '/partials/siteFooter.php'; ?>

    <script>
    window.addEventListener('load', function() {
        var navElement = document.getElementById('nav');
        var mainElement = document.getElementById('main');
        if (mainElement) {
            mainElement.classList.remove('hidden-until-loaded');
            mainElement.classList.add('show-after-load');
        }
        if (navElement) {
            navElement.classList.remove('hidden-until-loaded');
            navElement.classList.add('show-after-load');
        }
        if (window.lucide) {
            window.lucide.createIcons();
        }
    });
    </script>
</body>
</html>
