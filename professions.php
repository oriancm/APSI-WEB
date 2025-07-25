<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>APSI BTP - Nos Métiers</title>
    <link rel="stylesheet" href="/css/professions.css">
    <link rel="stylesheet" href="/css/dropdown-menus.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:bold">
    <style>
      .hidden-until-loaded {
        opacity: 0;
        transition: opacity 0.3s;
      }
      .show-after-load {
        opacity: 1 !important;
      }
    </style>
</head>
<body>
    <?php include "./nav.php"; ?>

    <main id="main" class="scrolled hidden-until-loaded">
        <section>
            <div class="content">
                <h1>Nos Métiers</h1>

                <!-- Section OPC -->
                <div class="dropdown-section">
                    <h2 class="dropdown-toggle" onclick="toggleDropdown('opc-content')">
                        Ordonnancement, Pilotage et Coordination (OPC)
                        <span class="dropdown-arrow">▼</span>
                    </h2>
                    <div id="opc-content" class="dropdown-content">
                        <h3>Définition de la mission OPC</h3>
                        <p><i>Quels sont les objectifs de l'Ordonnancement, Pilotage et Coordination ?</i></p>
                        <p><b>L'Ordonnancement, Pilotage et Coordination</b>, plus connue sous son acronyme <b>OPC</b>, est une mission clé en phase de chantier, aussi bien pour des projets de <b>construction</b> que de <b>rénovation</b>. Son objectif est d'assurer <b>l'organisation</b> et <b>le suivi</b> au quotidien des entreprises en charge de l'exécution d'un ou plusieurs lots techniques, afin de respecter les délais du projet.</p>
                        <p>Cette étape importante est définie et encadrée par un texte de loi, le <b>décret n°93-1268</b> qui indique que :</p>
                        <p>« <b>L'ordonnancement, la coordination et le pilotage du chantier</b> ont respectivement pour objet :</p>
                        <ul>
                            <li>d'analyser les tâches élémentaires portant sur les études d'exécution et les travaux, de déterminer leurs enchaînements ainsi que leur chemin critique,</li>
                            <li>d'harmoniser dans le temps et dans l'espace les actions des différents intervenants ;</li>
                            <li>au stade des travaux et jusqu'à la levée des réserves, de mettre en application les diverses mesures au titre de l'ordonnancement et de la coordination. »</li>
                        </ul>

                        <img src="img\chantier.jpg" alt="Chantier" class="chantier-image">
                        <h3>Périmètre de la mission OPC</h3>
                        <p><i>En quoi consiste la mission OPC ?</i></p>
                        <p>La mission <b>OPC</b> consiste, comme son nom l'indique, à <b>ordonner</b>, <b>piloter</b> et <b>coordonner</b> un projet :</p>
                        <ul>
                            <li><b>L'ordonnancement</b> représente une phase plutôt amont lors de laquelle le coordonnateur OPC va participer aux études de conception. L'objectif est d'analyser chaque <b>tâche</b>, les <b>demandes administratives</b> à obtenir, les <b>contraintes naturelles</b> du projet. Ce travail va permettre de commencer à établir un <b>planning prévisionnel</b> afin de bien gérer l'exécution des travaux et l'enchaînement des différents <b>prestataires</b>, dépendants de l'avancée des uns et des autres. Enfin cette étape va permettre d'avoir une idée un peu plus fine de la date de <b>livraison</b> prévue.</li>
                            
                            <img src="img\gantt.jpg" alt="Chantier" class="chantier-image">

                            <li><b>Le pilotage</b> est la gestion quotidienne des travaux jusqu'à la livraison du projet et la levée des réserves.</li>
                            <li><b>La coordination</b> est une mission qui s'opère au quotidien avec les multiples intervenants qui vont intervenir sur l'opération :<ul>
                                    <li>L'Architecte</li>
                                    <li>Les Bureaux d'Etudes Techniques (fluides, structures, sols, amiante et plomb, etc…)</li>
                                    <li>Le Bureau de Contrôle</li>
                                    <li>Le Coordonnateur SPS</li>
                                    <li>Les entreprises titulaires des marchés de travaux</li>
                                    <li>Le chef d'établissement et les exploitants dans le cas d'interventions sur un site occupé et en activité</li>
                                    <li>Les concessionnaires des réseaux publics sur lesquelles doivent se raccorder le projet</li>
                                </ul>
                                Elle découle naturellement de la <b>phase d'ordonnancement</b> car elle s'appuie sur le planning qui aura été établi. Toutefois, c'est une étape un peu plus opérationnelle car elle va gérer le projet selon son avancée réelle.
                            </li>
                        </ul>

                        <h3>Quand commence-t-on la mission OPC ?</h3>
                        <p>La mission d'<b>Ordonnancement Pilotage et Coordination</b> commence dès la phase de conception du projet par l'équipe de maîtrise d'œuvre.</p>
                        <p>En effet, il est pertinent de consulter le coordonnateur OPC du projet pour prévoir dès lors un calendrier général de l'opération et ainsi commencer à identifier une date de livraison du projet.</p>

                        <h3>Les grandes étapes de la mission OPC</h3>
                        <ol>
                            <li><b><span class="phase-title">PHASE DE CONCEPTION DU PROJET</span></b>
                                <ul>
                                    <li>Établissement et mises à jour du <b>calendrier général de l'opération</b> (depuis la phase DIAG/APS jusqu'à la livraison de l'opération, et incluant la phase de consultation des entreprises et celle relative au Permis de Construire ou d'Aménager)</li>
                                    <li><b>Calendrier enveloppe par corps d'état</b></li>
                                    <li>Établissement d'un plan de principe d'organisation et d'installation de chantier soumis à l'avis du Coordonnateur SPS</li>
                                    <li><b>Assistance à la rédaction des documents contractuels</b> des marchés de travaux (AE, CCAP, CCTP, etc…) en matière d'organisation et de délai</li>
                                    <li>Assistance à l'élaboration du <b>carnet de phasage</b> dans le cas d'opération de réhabilitation en site occupé avec maintien de l'activité (y compris indication des contraintes fortes d'organisation à intégrer dans les documents de consultation des entreprises)</li>
                                    <li>Participation à des réunions en phase conception avec les différents intervenants du projet (maître d'ouvrage, Architecte, bureaux d'études techniques, contrôleur technique, coordonnateur SPS, etc…) de manière à bien assimiler les caractéristiques des travaux projetés et appréhender les contraintes spécifiques en matière d'organisation générale du chantier</li>
                                </ul>
                            </li>
                            <img src="img\gantt2.jpg" alt="Chantier" class="chantier-image">

                            <li><b><span class="phase-title">PERIODE DE PREPARATION DE CHANTIER</span></b>
                                <ul>
                                    <li><b>Élaboration du calendrier détaillé des études d'exécution</b> avec les dates incombant à chaque intervenant</li>
                                    <li>Suivi du <b>calendrier des études d'exécution</b></li>
                                    <li>Établissement de <b>rapports périodiques</b> d'avancement des études d'exécution</li>
                                    <li><b>Participation et animation des réunions de préparation</b></li>
                                    <li>Diffusion des <b>comptes-rendus</b></li>
                                </ul>
                            </li>
                            <li><b><span class="phase-title">PHASE D'EXECUTION DES TRAVAUX</span></b>
                                <ul>
                                    <li>Animation des réunions de coordination, rédaction et diffusion des compte-rendus</li>
                                    <li><b>Pointage</b> hebdomadaire des différentes interventions, recensement des écarts constatés et repérage de leur origine</li>
                                    <li>Proposition de <b>mesures correctives</b> pour rattraper les retards</li>
                                    <li><b>Recalage des calendriers</b> en fonction des besoins</li>
                                    <li>Contrôle de l'entretien et du <b>nettoyage du chantier</b></li>
                                    <li>Appréciation des responsabilités concernant les <b>retards</b> constatés et proposition d'application de <b>pénalités éventuelles</b></li>
                                </ul>
                            </li>
                            <li><b><span class="phase-title">OPERATIONS DE RECEPTION</span></b>
                                <ul>
                                    <li>Etablissement de calendriers détaillés faisant notamment apparaître :
                                        <ul>
                                            <li><b>Les Opérations Préalables à la Réception</b></li>
                                            <li>Les essais de mise en service, vérifications techniques</li>
                                            <li>Les opérations de réception et <b>levées de réserves</b></li>
                                            <li>Les visites des commissions de sécurité</li>
                                            <li>La fourniture des <b>Dossiers des Ouvrages Exécutés</b> (DOE)</li>
                                            <li>Le repliement des installations de chantier et la remise en état des lieux</li>
                                        </ul>
                                    </li>
                                    <li>Animation des réunions pendant la période de levée des réserves, rédaction et diffusion des compte-rendus</li>
                                </ul>
                            </li>
                        </ol>

                        <h3>Les atouts de la mission OPC chez APSI BTP :</h3>
                        <ul>
                            <li>La garantie du <b>respect des délais et des objectifs</b> fixés</li>
                            <li>Un <b>travail d'équipe en étroite collaboration avec l'Architecte</b> et/ou le Maître d'Œuvre, mais également avec les autres intervenants (représentant du maître d'ouvrage, contrôleur technique, coordonnateur SPS, bureaux d'études techniques, concessionnaires, etc…)</li>
                            <li>Une <b>forte implication</b> au quotidien pour gérer et résoudre les nombreuses difficultés et aléas qui ne manqueront pas d'apparaître tout au long de l'exécution des travaux</li>
                            <li><b>L'anticipation</b> de nombreux sujets de manière à faciliter et fluidifier le bon déroulement des travaux (à l'instar d'un « démineur » qui prépare l'avancée des troupes)</li>
                            <li>Une <b>grande disponibilité</b> suivi d'une <b>forte réactivité</b></li>
                            <li>Une <b>capacité d'adaptation</b> élevée en fonction des changements voulus par nos clients ou imposés par des évènements extérieurs (intempéries, intervention à l'intérieur d'un site en activité, rupture d'approvisionnement par les fournisseurs, grèves, etc…)</li>
                            <li>L'assurance d'une <b>planification détaillée, ambitieuse et réaliste</b></li>
                            <li><b>Des compétences techniques reconnues</b> qui permettent d'orienter, en collaboration avec l'Architecte et/ou le Maître d'Œuvre, le choix des méthodes de construction dans le seul but d'optimiser le délai d'exécution des travaux en évitant également des frais supplémentaires</li>
                            <li><b>Des relations humaines saines et respectueuses</b> avec les différents intervenants</li>
                            <li><b>Des comptes-rendus clairs et précis</b> des réunions hebdomadaires</li>
                            <li><b>La traçabilité</b> des décisions prises en réunion et de leurs retranscriptions sur le terrain</li>
                            <li>Un accompagnement possible pendant la période de Garantie de Parfait Achèvement</li>
                        </ul>
                    </div>
                </div>

                <!-- Section MOEX -->
                <div class="dropdown-section">
                    <h2 class="dropdown-toggle" onclick="toggleDropdown('moex-content')">
                        Maîtrise d'Œuvre d'Exécution (MOEX)
                        <span class="dropdown-arrow">▼</span>
                    </h2>
                    <div id="moex-content" class="dropdown-content">
                        <h3>Définition de la mission MOEX</h3>
                        <p><b>La Maîtrise d'Œuvre d'Exécution (MOEX)</b>, ou Direction de l'Exécution des Travaux (DET), consiste à s'assurer que les travaux respectent les plans, les matériaux et les normes réglementaires.</p>
                        <p>La maîtrise d’œuvre de conception va souvent de pair avec la maîtrise d’œuvre d’exécution. Tandis que la maîtrise d’œuvre de conception intervient en amont du projet (jusqu’à l’obtention du Permis de Construire ou d’Aménager et à la consultation des entreprises), la maîtrise d’œuvre d’exécution, quant à elle, prend le relais lors de la <b>phase de réalisation</b>.</p>
                        <p>Elle incarne le lien entre la Maîtrise d’Ouvrage et les entreprises chargées des travaux.</p>
                        <p>Le maître d’œuvre d’exécution intervient dès l’ouverture du chantier jusqu’à sa fermeture dans le but de superviser les travaux.</p>
                        <h3>Prestations de la mission MOEX chez APSI BTP</h3>
                        <ul>
                            <li><b>Suivi et direction des travaux</b></li>
                            <li><b>Contrôle de la qualité</b> des travaux</li>
                            <li><b>Respect des normes</b> de construction (DTU, Normes Françaises, Avis Techniques)</li>
                            <li><b>Coordination</b> des travaux entre les entreprises</li>
                            <li>Veille au <b>respect des consignes d'hygiène et de sécurité</b></li>
                            <li>Tenue des <b>réunions hebdomadaires</b> de chantier</li>
                            <li><b>Gestion financière du chantier</b></li>
                            <li><b>Assistance au Maître d'Ouvrage</b> jusqu'à la réception et la levée des réserves</li>
                            <li>Vérification des décomptes généraux et définitifs (DGD)</li>
                            <li>Collecte et vérification des dossiers des ouvrages exécutés (DOE)</li>
                            <li>Assistance pendant l'année de parfait achèvement</li>
                        </ul>

                        <h3>Les avantages de la mission MOEX chez APSI BTP</h3>
                        <ul>
                            <li>Garantie du <b>respect des objectifs fixés</b></li>
                            <li><b>Forte implication</b> quotidienne pour gérer les aléas</li>
                            <li><b>Grande disponibilité</b></li>
                            <li><b>Gestion financière rigoureuse</b></li>
                            <li><b>Forte Capacity d'adaptation</b> face aux changements ou imprévus</li>
                            <li><b>Compétences techniques reconnues</b> pour optimiser les délais et maîtriser les coûts</li>
                            <li><b>Relations humaines respectueuses</b></li>
                            <li><b>Comptes-rendus clairs et précis</b></li>
                            <li><b>Traçabilité</b> des décisions prises</li>
                            <li>Accompagnement pendant la garantie de parfait achèvement</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="pb">
                <p class="slogan">APSI BTP, vos projets en toute sérénité…</p>
            </div>  
        </section>
    </main>

    <script>
        function toggleDropdown(contentId) {
            const content = document.getElementById(contentId);
            const arrow = content.previousElementSibling.querySelector('.dropdown-arrow');
            
            if (content.style.maxHeight) {
                content.style.maxHeight = null;
                arrow.style.transform = 'rotate(0deg)';
            } else {
                content.style.maxHeight = content.scrollHeight + 'px';
                arrow.style.transform = 'rotate(180deg)';
            }
        }

        window.addEventListener('load', function() {
            var navElement = document.getElementById('nav');
            var navHeight = navElement ? navElement.offsetHeight : 0;
            
            var mainElement = document.getElementById('main');
            if (mainElement) {
                mainElement.style.marginTop = navHeight + 'px';
                mainElement.classList.remove('hidden-until-loaded');
                mainElement.classList.add('show-after-load');
            }
            if (navElement) {
                navElement.classList.add('show-after-load');
            }
        });
    </script>
</body>
<link rel="stylesheet" href="/css/styleGlobalNotIndex.css">
</html>