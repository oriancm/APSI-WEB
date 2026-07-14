<?php $activePage = 'professions'; ?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Nos Métiers : OPC & MOEX - APSI BTP</title>
    <meta name="description" content="Découvrez les missions OPC et MOEX d'APSI BTP : coordination de chantier, planification, suivi d'exécution, réunions, OPR et levée des réserves.">

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
        <?= apsi_minify_css(__DIR__ . '/css/professions.css'); ?>
    </style>
    
    <!-- Fonts Preload -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap">
    </noscript>

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
                <img src="/img/professions-title-mobile.jpg" width="1024" height="616" alt="APSI BTP - Nos Métiers">
            </div>

            <p>
                APSI BTP accompagne les maîtres d’ouvrage publics et privés dans l’organisation,
                le pilotage et le suivi de leurs opérations de <b>construction</b> et de <b>réhabilitation</b>.
            </p>

            <p>
                Nos missions s’articulent autour de deux expertises complémentaires :
                <b>OPC</b> et <b>MOEX</b>.
            </p>
        </div>
    </section>

    <section class="profession-card profession-card--opc">
        <div class="profession-card-copy">
            <div class="profession-title-row">
                <h2>Ordonnancement,<br>Pilotage et Coordination<br><span>(OPC)</span></h2>
            </div>

            <p>
                La mission OPC organise les phases du chantier, coordonne les intervenants
                et suit l’avancement selon le planning établi.
            </p>

            <div class="profession-points">
                <div><i data-lucide="calendar-days" aria-hidden="true"></i><span>Planning<br>d’exécution</span></div>
                <div><i data-lucide="workflow" aria-hidden="true"></i><span>Coordination<br>des intervenants</span></div>
                <div><i data-lucide="clock-3" aria-hidden="true"></i><span>Suivi des délais<br>et ajustements</span></div>
            </div>
        </div>

        <img src="/img/opc-desktop.jpg" width="612" height="408" alt="Deux professionnels analysant des plans sur un chantier">
    </section>

    <section class="profession-card profession-card--moex">
        <img src="/img/moex-desktop.avif" width="1024" height="623" alt="Professionnel APSI BTP sur chantier avec tablette">

        <div class="profession-card-copy">
            <div class="profession-title-row">
                <h2>Maîtrise d’Œuvre<br>d’Exécution <span>(MOEX)</span></h2>
            </div>

            <p>
                La mission MOEX assure le suivi technique, administratif et financier
                des travaux jusqu’à la réception.
            </p>

            <div class="profession-points">
                <div><i data-lucide="shield-check" aria-hidden="true"></i><span>Contrôle<br>de conformité</span></div>
                <div><i data-lucide="bar-chart-3" aria-hidden="true"></i><span>Suivi technique<br>et financier</span></div>
                <div><i data-lucide="users" aria-hidden="true"></i><span>Réception<br>des travaux</span></div>
            </div>
        </div>
    </section>

    <section class="profession-opc-pillars">
        <div class="section-heading">
            <h2>Comprendre la mission OPC</h2>
            <p>
                L’OPC repose sur trois fonctions complémentaires : préparer, piloter
                et coordonner le chantier dans le temps et dans l’espace.
            </p>
        </div>

        <div class="opc-pillars-grid">
            <button class="opc-pillar active" type="button" data-panel="ordonnancement">
                <i data-lucide="list-checks" aria-hidden="true"></i>
                <span>Ordonnancement</span>
                <small>Analyser, phaser, planifier</small>
            </button>

            <button class="opc-pillar" type="button" data-panel="pilotage">
                <i data-lucide="activity" aria-hidden="true"></i>
                <span>Pilotage</span>
                <small>Suivre, ajuster, décider</small>
            </button>

            <button class="opc-pillar" type="button" data-panel="coordination">
                <i data-lucide="network" aria-hidden="true"></i>
                <span>Coordination</span>
                <small>Organiser les échanges</small>
            </button>
        </div>

        <div class="opc-panel active" id="ordonnancement">
            <h3>Ordonnancement</h3>
            <p>
                L’ordonnancement intervient en amont du chantier. Il consiste à analyser les tâches,
                les demandes administratives, les contraintes du site et les dépendances entre les entreprises.
            </p>
            <p>
                Cette phase permet d’établir un <b>calendrier prévisionnel</b>, d’anticiper les enchaînements
                entre prestataires et d’identifier une date de livraison réaliste.
            </p>
        </div>

        <div class="opc-panel" id="pilotage">
            <h3>Pilotage</h3>
            <p>
                Le pilotage correspond au suivi opérationnel du chantier. Il permet de contrôler l’avancement réel,
                de repérer les écarts et de proposer les ajustements nécessaires.
            </p>
            <p>
                Cette mission se poursuit jusqu’à la livraison, aux opérations préalables à la réception
                et à la <b>levée des réserves</b>.
            </p>
        </div>

        <div class="opc-panel" id="coordination">
            <h3>Coordination</h3>
            <p>
                La coordination s’exerce avec l’ensemble des acteurs du projet : architecte,
                <span class="acronym" data-definition="Bureau d’Études Techniques">BET</span>,
                bureau de contrôle,
                <span class="acronym" data-definition="Sécurité et Protection de la Santé">SPS</span>,
                entreprises, exploitants en site occupé et concessionnaires.
            </p>
            <p>
                Elle s’appuie sur le planning établi, mais s’adapte en permanence à la
                <b>réalité du chantier</b> et à l’avancement des interventions.
            </p>
        </div>
    </section>

    <section class="profession-project-flow">
        <div class="section-heading">
            <h2>Notre intervention au fil du projet</h2>
            <p>
                De la conception à la réception, APSI BTP structure l’organisation du chantier,
                suit l’avancement et accompagne les acteurs du projet.
            </p>
        </div>

        <div class="project-steps" aria-label="Étapes d'intervention">
            <button class="project-step active" type="button" data-step="conception">
                <span>01</span>
                <strong>Conception</strong>
                <small>Anticiper l’organisation</small>
            </button>

            <button class="project-step" type="button" data-step="preparation">
                <span>02</span>
                <strong>Préparation</strong>
                <small>Structurer le démarrage</small>
            </button>

            <button class="project-step" type="button" data-step="execution">
                <span>03</span>
                <strong>Exécution</strong>
                <small>Suivre et ajuster</small>
            </button>

            <button class="project-step" type="button" data-step="reception">
                <span>04</span>
                <strong>Réception</strong>
                <small>Clôturer l’opération</small>
            </button>
        </div>

        <div class="project-panel active" id="conception">
            <h3>Conception</h3>
            <p>
                En phase de conception, APSI BTP accompagne la maîtrise d’ouvrage et la maîtrise d’œuvre
                pour intégrer les contraintes d’organisation dans le projet.
            </p>
            <p>
                Cette étape permet de travailler sur le <b>calendrier général</b>, le phasage des travaux,
                les contraintes de site, les accès, les demandes administratives et les délais liés aux études
                ou aux autorisations.
            </p>
            <p>
                L’objectif est d’anticiper les conditions d’exécution avant le
                <span class="acronym" data-definition="Dossier de Consultation des Entreprises">DCE</span>,
                afin de limiter les aléas techniques, financiers et calendaires pendant le chantier.
            </p>
        </div>

        <div class="project-panel" id="preparation">
            <h3>Préparation de chantier</h3>
            <p>
                Pendant la période de préparation, APSI BTP établit et suit les premiers calendriers
                opérationnels du chantier.
            </p>
            <p>
                Cette phase comprend notamment le calendrier des études
                <span class="acronym" data-definition="Études d’exécution">EXE</span>,
                l’organisation des réunions de préparation, la coordination des entreprises,
                la diffusion des comptes-rendus et l’identification des points à traiter avant le démarrage effectif.
            </p>
            <p>
                Elle permet de clarifier les responsabilités, les délais de transmission des documents,
                les contraintes d’intervention et l’<b>enchaînement des premiers travaux</b>.
            </p>
        </div>

        <div class="project-panel" id="execution">
            <h3>Exécution des travaux</h3>
            <p>
                En phase travaux, APSI BTP assure le suivi opérationnel du chantier.
            </p>
            <p>
                Cette mission repose sur l’animation des réunions de coordination, le pointage régulier
                de l’avancement, l’analyse des écarts entre le planning prévu et la réalité du terrain,
                ainsi que la proposition de <b>mesures correctives</b> en cas de retard ou de difficulté.
            </p>
            <p>
                Le planning est recalé lorsque nécessaire pour tenir compte des contraintes techniques,
                des interfaces entre entreprises, des aléas de chantier ou des évolutions demandées
                par le maître d’ouvrage.
            </p>
            <p>
                Cette phase implique des échanges réguliers avec l’architecte, les
                <span class="acronym" data-definition="Bureaux d’Études Techniques">BET</span>,
                le bureau de contrôle, le coordonnateur
                <span class="acronym" data-definition="Sécurité et Protection de la Santé">SPS</span>,
                les entreprises et, lorsque le site reste occupé, les exploitants ou utilisateurs.
            </p>
        </div>

        <div class="project-panel" id="reception">
            <h3>Réception</h3>
            <p>
                En fin d’opération, APSI BTP accompagne les opérations préalables à la réception
                et la clôture du chantier.
            </p>
            <p>
                Cette étape comprend l’établissement des calendriers de réception, le suivi des essais
                et vérifications techniques, l’organisation des visites, le suivi des réserves,
                la coordination des interventions correctives et la collecte des documents de fin de chantier.
            </p>
            <p>
                L’objectif est d’assurer une transition claire entre la fin des travaux,
                la réception officielle, la <b>levée des réserves</b> et la remise des documents
                nécessaires au maître d’ouvrage.
            </p>
        </div>
    </section>

    <section class="profession-deliverables">
        <div class="profession-deliverables-left">
            <h2>Documents et suivi de mission</h2>
            <div class="deliverables-image-wrap">
                <img src="/img/illustrations-articles-de-blog.png" width="550" height="400" alt="Outils de documents et suivi de mission - APSI BTP">
            </div>
        </div>

        <div class="profession-deliverables-right">
            <p>
                Nos missions s’appuient sur des outils de suivi clairs, partagés avec les acteurs du projet.
            </p>
            <ul>
                <li>Calendrier général de l’opération</li>
                <li>Planning détaillé d’exécution</li>
                <li>Calendrier des études d’exécution</li>
                <li>Comptes-rendus de réunions de chantier</li>
                <li>Pointage de l’avancement et analyse des écarts</li>
                <li>Planning <span class="acronym" data-definition="Opérations Préalables à la Réception">OPR</span>, réception et levée des réserves</li>
                <li>Suivi des <span class="acronym" data-definition="Dossiers des Ouvrages Exécutés">DOE</span> et clôture administrative</li>
            </ul>
        </div>
    </section>

    <section class="profession-assets">
        <div class="profession-assets-heading">
            <h2>Une coordination de terrain</h2>
            <p>
                APSI BTP assure une présence régulière, des échanges quotidiens avec les intervenants
                et une adaptation constante aux réalités du chantier.
            </p>
        </div>

        <div class="profession-assets-grid">
            <div><i data-lucide="hard-hat" aria-hidden="true"></i><span>Présence<br>terrain</span></div>
            <div><i data-lucide="messages-square" aria-hidden="true"></i><span>Échanges<br>quotidiens</span></div>
            <div><i data-lucide="search" aria-hidden="true"></i><span>Anticipation<br>des contraintes</span></div>
            <div><i data-lucide="timer" aria-hidden="true"></i><span>Suivi<br>des délais</span></div>
            <div><i data-lucide="clipboard-check" aria-hidden="true"></i><span>Réception<br>des travaux</span></div>
        </div>
    </section>

    <section class="profession-cta">
        <div class="profession-cta-content">
            <i data-lucide="messages-square" aria-hidden="true"></i>

            <div class="profession-cta-text">
                <h2>Un accompagnement dédié<br>pour vos opérations</h2>
                <p><b>APSI BTP</b> vous accompagne dans l’organisation,<br>le pilotage et le suivi de vos chantiers.</p>
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

document.addEventListener('DOMContentLoaded', function() {
    function initToggleGroup(buttonSelector, panelSelector, dataKey) {
        const buttons = document.querySelectorAll(buttonSelector);
        const panels = document.querySelectorAll(panelSelector);

        function handlePanelPosition() {
            const isMobile = window.innerWidth <= 850;
            buttons.forEach((button) => {
                if (button.classList.contains('active')) {
                    const target = button.dataset[dataKey];
                    const panel = document.getElementById(target);
                    if (panel) {
                        if (isMobile) {
                            // Move panel immediately after the active button on mobile
                            button.parentNode.insertBefore(panel, button.nextSibling);
                        } else {
                            // Move panel back to the end of its parent section on desktop
                            const section = button.closest('section');
                            section.appendChild(panel);
                        }
                    }
                }
            });
        }

        buttons.forEach((button) => {
            button.addEventListener('click', function() {
                const target = this.dataset[dataKey];

                buttons.forEach((btn) => btn.classList.remove('active'));
                panels.forEach((panel) => panel.classList.remove('active'));

                this.classList.add('active');

                const panel = document.getElementById(target);
                if (panel) {
                    panel.classList.add('active');
                }

                handlePanelPosition();
            });
        });

        // Handle resize events to reposition panels if necessary
        window.addEventListener('resize', handlePanelPosition);
        handlePanelPosition();
    }

    initToggleGroup('.opc-pillar', '.opc-panel', 'panel');
    initToggleGroup('.project-step', '.project-panel', 'step');
});
</script>
</body>
</html>