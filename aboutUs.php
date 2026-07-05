<?php $activePage = 'about'; ?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>APSI BTP - Qui sommes-nous | OPC et MOEX depuis 2007</title>
    <meta name="description" content="APSI BTP accompagne les maîtres d’ouvrage publics et privés en OPC et MOEX depuis 2007, avec plus de 100 opérations suivies en construction et réhabilitation.">

    <link rel="stylesheet" href="/css/site.css">
    <link rel="stylesheet" href="/css/aboutUs.css?v=20260623-2">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap">

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>
    <script src="/js/site.js" defer></script>
</head>

<body class="about-page">
<main id="main" class="about-page-shell site-shell-bg hidden-until-loaded">

    <?php include __DIR__ . '/partials/siteHeader.php'; ?>

    <section class="about-hero">
        <div class="about-hero-content">
            <div class="about-hero-copy">
                <h1>Qui sommes-nous</h1>

                <p>
                    Créée en 2007 par <b>Ludovic MESSY</b>, APSI BTP accompagne les maîtres d’ouvrage,
                    architectes et équipes de maîtrise d’œuvre dans le suivi de leurs opérations.
                </p>

                <p>
                    Depuis 2021, <b>Laurent MÉTAYER</b> a rejoint la société pour renforcer son développement
                    et poursuivre l’accompagnement des projets en construction et réhabilitation.
                </p>
            </div>
            <div class="about-founders-row">
                <div class="about-founder-profile">
                    <div class="about-founder-photo-frame">
                        <img src="/img/ludovic.jpg" alt="Ludovic MESSY - Fondateur de APSI BTP">
                    </div>
                    <div class="about-founder-info">
                        <strong>Ludovic MESSY</strong>
                        <span>Fondateur</span>
                    </div>
                </div>
                <div class="about-founder-profile about-founder-placeholder">
                    <div class="about-founder-photo-frame placeholder-frame">
                        <div class="founder-placeholder-logo">LM</div>
                    </div>
                    <div class="about-founder-info">
                        <strong>Laurent MÉTAYER</strong>
                        <span>OPC Associé</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="about-badges" aria-label="Chiffres clés">
        <article>
            <i data-lucide="calendar-days" aria-hidden="true"></i>
            <div>
                <strong>20+ ans</strong>
                <span>D'expérience</span>
            </div>
        </article>

        <article>
            <i data-lucide="award" aria-hidden="true"></i>
            <div>
                <strong>100+</strong>
                <span>Références</span>
            </div>
        </article>
    </section>

    <section class="about-card about-missions">
        <div class="about-section-title">
            <h2>Nos missions</h2>
        </div>

        <div class="about-missions-grid">
            <div class="about-missions-left">
                <p>
                    APSI BTP intervient sur des opérations publiques et privées, principalement autour
                    de deux missions complémentaires.
                </p>

                <div class="mission-item">
                    <i data-lucide="calendar-clock" aria-hidden="true"></i>
                    <strong>Ordonnancement, Pilotage<br>et Coordination (OPC)</strong>
                </div>

                <div class="mission-item">
                    <i data-lucide="building-2" aria-hidden="true"></i>
                    <strong>Maîtrise d’Œuvre<br>d’Exécution (MOEX)</strong>
                </div>
            </div>

            <!-- Mobile Image Section -->
            <div class="about-missions-mobile-image">
                <img src="/img/opc-chantier.jpg" alt="Suivi de chantier et Ordonnancement, Pilotage, Coordination (OPC) - APSI BTP">
            </div>

            <div class="about-missions-text">
                <p>
                    APSI BTP s’est particulièrement spécialisée dans les missions <b>OPC</b>,
                    avec une approche centrée sur <b>l’organisation</b>,
                    <b>la planification</b> et le <b>suivi opérationnel</b> du chantier.
                </p>

                <p>
                    Nous intervenons dès les phases de <b>conception</b> afin d’intégrer les contraintes
                    d’<b>organisation</b>, de <b>phasage</b> et de <b>calendrier</b> dans les pièces de consultation.
                    Cette anticipation permet de limiter les <b>aléas techniques</b>,
                    <b>financiers</b> et <b>calendaires</b> pendant les travaux.
                </p>

                <p>
                    Sur le terrain, notre rôle est d’assurer une <b>coordination claire</b>
                    entre les différents intervenants, tout en défendant les intérêts du
                    <b>maître d’ouvrage</b> et en maintenant un <b>dialogue constructif</b>
                    avec les entreprises.
                </p>
            </div>
        </div>
    </section>

    <section class="about-lower-grid">
        <article class="about-card about-experience">
            <div class="about-section-title">
                <h2>Notre expérience</h2>
            </div>

            <p>
                Avec plus d’une centaine d’opérations suivies, APSI BTP a développé une expérience
                concrète sur des projets variés, en construction neuve comme en réhabilitation.
            </p>

            <ul>
                <li>
                    <i data-lucide="building-2" aria-hidden="true"></i>
                    <span><b>Constructions neuves</b> : logements, EHPAD, groupes scolaires, gymnases, bâtiments tertiaires.</span>
                </li>

                <li>
                    <i data-lucide="building" aria-hidden="true"></i>
                    <span><b>Réhabilitations en site occupé</b> : logements sociaux, hôpitaux, cliniques, collèges et écoles.</span>
                </li>

                <li>
                    <i data-lucide="bed" aria-hidden="true"></i>
                    <span><b>Hôtellerie</b> : établissements de charme et projets haut de gamme.</span>
                </li>

                <li>
                    <i data-lucide="home" aria-hidden="true"></i>
                    <span><b>Patrimoine privé</b> : maisons de maître et demeures provençales de caractère.</span>
                </li>

                <li>
                    <i data-lucide="trees" aria-hidden="true"></i>
                    <span><b>Aménagements urbains</b> : interventions en centre-ville et espaces publics.</span>
                </li>
            </ul>
        </article>

        <article class="about-card about-zone">
            <div class="about-section-title">
                <h2>Notre zone d’intervention</h2>
            </div>

            <p>
                APSI BTP privilégie un rayon d’action maîtrisé afin de garantir une présence régulière
                sur chantier, une bonne réactivité et un suivi de proximité.
            </p>

            <div class="zone-layout">
                <ul class="dept-list">
                    <li data-dept="84"><i data-lucide="map-pin" aria-hidden="true"></i><span>Vaucluse <b>(84)</b></span></li>
                    <li data-dept="04"><i data-lucide="map-pin" aria-hidden="true"></i><span>Alpes-de-Haute-Provence <b>(04)</b></span></li>
                    <li data-dept="05"><i data-lucide="map-pin" aria-hidden="true"></i><span>Hautes-Alpes <b>(05)</b></span></li>
                    <li data-dept="13"><i data-lucide="map-pin" aria-hidden="true"></i><span>Bouches du Rhône <b>(13)</b></span></li>
                    <li data-dept="30"><i data-lucide="map-pin" aria-hidden="true"></i><span>Gard <b>(30)</b></span></li>
                    <li data-dept="83"><i data-lucide="map-pin" aria-hidden="true"></i><span>Var <b>(83)</b></span></li>
                </ul>

                <div class="interactive-map" aria-label="Carte interactive des départements d'intervention">
                    <svg viewBox="0 0 400 360" role="img" aria-labelledby="map-title">
                        <title id="map-title">Carte interactive PACA et Gard</title>
                        <path class="sea" d="M0 285 Q70 260 135 300 T280 318 T400 290 L400 360 L0 360 Z"></path>

                        <g class="dept" data-dept="30">
                            <path d="M38 108 L112 120 L145 190 L110 226 L82 292 L48 275 Q18 235 24 172 Z"></path>
                            <text x="76" y="198">30</text>
                        </g>

                        <g class="dept active" data-dept="84">
                            <path d="M112 120 L190 110 L232 143 L232 214 L206 246 L138 248 L145 190 Z"></path>
                            <text x="165" y="187">84</text>
                        </g>

                        <g class="dept" data-dept="13">
                            <path d="M138 248 L206 246 L218 264 L244 264 L236 310 L186 304 L145 330 L84 292 L110 226 Z"></path>
                            <text x="160" y="291">13</text>
                        </g>

                        <g class="dept" data-dept="05">
                            <path d="M226 30 L312 52 L348 92 L316 158 L254 148 L214 108 Z"></path>
                            <text x="275" y="97">05</text>
                        </g>

                        <g class="dept" data-dept="04">
                            <path d="M190 110 L254 148 L316 158 L350 230 L308 260 L250 268 L232 214 L232 143 Z"></path>
                            <text x="278" y="212">04</text>
                        </g>

                        <g class="dept" data-dept="83">
                            <path d="M236 310 L244 264 L250 268 L308 260 L335 288 C350 312 326 344 296 348 L218 330 Z"></path>
                            <text x="272" y="315">83</text>
                        </g>
                    </svg>

                    <div class="map-info" id="map-info">Vaucluse (84)</div>
                </div>
            </div>
        </article>
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
    const labels = {
        '84': 'Vaucluse (84)',
        '04': 'Alpes-de-Haute-Provence (04)',
        '05': 'Hautes-Alpes (05)',
        '13': 'Bouches du Rhône (13)',
        '30': 'Gard (30)',
        '83': 'Var (83)'
    };

    const mapInfo = document.getElementById('map-info');
    const targets = document.querySelectorAll('.dept, .dept-list li');

    function setActive(code) {
        document.querySelectorAll('.dept, .dept-list li').forEach((el) => {
            el.classList.toggle('active', el.dataset.dept === code);
        });

        if (mapInfo) {
            mapInfo.textContent = labels[code] || '';
        }
    }

    targets.forEach((el) => {
        el.addEventListener('mouseenter', () => setActive(el.dataset.dept));
        el.addEventListener('focus', () => setActive(el.dataset.dept));
        el.addEventListener('click', () => setActive(el.dataset.dept));
        el.setAttribute('tabindex', '0');
    });
});
</script>
</body>
</html>