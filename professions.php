<?php $activePage = 'professions'; ?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>APSI BTP - Nos Métiers</title>
    <link rel="stylesheet" href="/css/site.css">
    <link rel="stylesheet" href="/css/professions.css?v=20260623-3">
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
            
            <div class="professions-hero-mobile-image">
                <img src="/img/professions-title-mobile.jpg" alt="APSI BTP - Nos Métiers">
            </div>

            <p>APSI BTP accompagne les maîtres d'ouvrage publics et privés dans le suivi de leurs opérations de <b>construction</b> et de <b>réhabilitation</b>.</p>
            <p>Nous intervenons sur les missions <b>OPC</b> et <b>MOEX</b>, de la préparation du chantier jusqu'à la réception des travaux.</p>
        </div>
    </section>

    <section class="profession-card profession-card--opc">
        <div class="profession-card-copy">
            <div class="profession-title-row">
                <h2>Ordonnancement,<br>Pilotage et Coordination<br><span>(OPC)</span></h2>
            </div>

            <p>La mission OPC organise les différentes phases du chantier et coordonne les interventions des entreprises selon le planning établi.</p>

            <div class="profession-points">
                <div><i data-lucide="calendar-days" aria-hidden="true"></i><span>Planning<br>d'exécution</span></div>
                <div><i data-lucide="workflow" aria-hidden="true"></i><span>Coordination<br>des intervenants</span></div>
                <div><i data-lucide="clock-3" aria-hidden="true"></i><span>Suivi des délais<br>et ajustements</span></div>
            </div>
        </div>

        <img src="/img/opc-desktop.jpg" alt="Deux professionnels analysant des plans sur un chantier">
    </section>

    <section class="profession-card profession-card--moex">
        <img src="/img/moex-desktop.jpg" alt="Professionnel APSI BTP sur chantier avec tablette">

        <div class="profession-card-copy">
            <div class="profession-title-row">
                <h2>Maîtrise d'Œuvre<br>d'Exécution <span>(MOEX)</span></h2>
            </div>

            <p>La mission MOEX assure le suivi de l'exécution des travaux, vérifie leur conformité et accompagne le maître d'ouvrage jusqu'à la réception.</p>

            <div class="profession-points">
                <div><i data-lucide="shield-check" aria-hidden="true"></i><span>Contrôle<br>de conformité</span></div>
                <div><i data-lucide="bar-chart-3" aria-hidden="true"></i><span>Suivi technique<br>et financier</span></div>
                <div><i data-lucide="users" aria-hidden="true"></i><span>Réception<br>des travaux</span></div>
            </div>
        </div>
    </section>

    <section class="profession-scope">
        <div class="profession-scope-heading">
            <h2>Concrètement, notre intervention</h2>
            <p>Nos missions s’adaptent à l’avancement du projet, depuis la préparation du chantier jusqu’aux opérations de réception.</p>
        </div>

        <div class="profession-scope-grid">
            <div>
                <span>01</span>
                <h3>Préparer</h3>
                <p>Analyse des contraintes, organisation des phases et mise en place des premiers calendriers.</p>
            </div>

            <div>
                <span>02</span>
                <h3>Coordonner</h3>
                <p>Organisation des interventions entre les entreprises, la maîtrise d’œuvre et la maîtrise d’ouvrage.</p>
            </div>

            <div>
                <span>03</span>
                <h3>Suivre</h3>
                <p>Contrôle de l’avancement, animation des réunions et identification des écarts éventuels.</p>
            </div>

            <div>
                <span>04</span>
                <h3>Réceptionner</h3>
                <p>Accompagnement lors des opérations préalables à la réception, levée des réserves et clôture du chantier.</p>
            </div>
        </div>
    </section>

    <section class="profession-deliverables">
        <div>
            <h2>Documents et suivi de mission</h2>
            <p>APSI BTP assure un suivi structuré du chantier à travers des outils clairs, partagés avec les différents acteurs du projet.</p>
        </div>

        <ul>
            <li>Calendriers généraux et plannings détaillés</li>
            <li>Comptes-rendus de réunions de chantier</li>
            <li>Suivi des études d’exécution et des interventions</li>
            <li>Pointage de l’avancement et analyse des écarts</li>
            <li>Suivi des réserves jusqu’à leur levée</li>
        </ul>
    </section>

    <section class="profession-assets">
        <div class="profession-assets-heading">
            <h2>Notre approche</h2>
            <p>Assurer un suivi clair, coordonné et adapté aux réalités du chantier.</p>
        </div>

        <div class="profession-assets-grid">
            <div><i data-lucide="hard-hat" aria-hidden="true"></i><span>Présence<br>terrain</span></div>
            <div><i data-lucide="timer" aria-hidden="true"></i><span>Organisation<br>du chantier</span></div>
            <div><i data-lucide="search" aria-hidden="true"></i><span>Anticipation<br>des contraintes</span></div>
            <div><i data-lucide="clipboard-check" aria-hidden="true"></i><span>Comptes-rendus<br>structurés</span></div>
            <div><i data-lucide="handshake" aria-hidden="true"></i><span>Interface<br>projet</span></div>
        </div>
    </section>

    <section class="profession-cta">
        <div class="profession-cta-content">
            <i data-lucide="messages-square" aria-hidden="true"></i>
            <div class="profession-cta-text">
                <h2>Un accompagnement dédié<br>pour vos opérations</h2>
                <p><b>APSI BTP</b> vous accompagne dans le pilotage<br>et le suivi de vos chantiers.</p>
            </div>
        </div>

        <a href="/contact" class="profession-cta-btn">
            <span>Nous contacter</span>
            <i data-lucide="arrow-right" aria-hidden="true"></i>
        </a>
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