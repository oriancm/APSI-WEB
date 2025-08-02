<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>APSI BTP - Mentions Légales</title>
    <link rel="stylesheet" href="css/legalNotices.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:bold">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
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
                <h1>Mentions légales</h1>
                
                <div class="legal-section">
                    <h2>Éditeur du site</h2>
                    <p>Le présent site est édité par <strong>APSI BTP</strong>, société de type <strong>SARL</strong>, au capital de <strong>6 800,00 €</strong></p>
                    <p>Siège social : Quartier Pierrefeu, chemin de Saint Jean, 84750 CASENEUVE</p>
                    <p>RCS Avignon : <strong> 2007 B 498</strong></p>
                    <p>Numéro de TVA : <strong>FR 40 495 392 557</strong></p>
                    <p>Directeur de la publication : <strong>Ludovic MESSY</strong></p>
                    <p>Contact : l.messy@apsi-btp.fr</p>
                </div>
                
                <div class="legal-section">
                    <h2>Hébergement</h2>
                    <p>Le site est hébergé par : <strong>OVH</strong></p>
                    <p>Adresse : 2 rue Kellermann, 59100 Roubaix, France</p>
                    <p>Site web : <strong><a href="https://www.ovh.com" target="_blank">https://www.ovh.com</a></strong></p>
                    <p>Téléphone : <strong>1007</strong> (gratuit depuis un poste fixe en France)</p>
                </div>
            </div>
            <div class="pb">
                <p class="slogan">APSI BTP, vos projets en toute sérénité…</p>
            </div>
        </section>
    </main>

</body>
<link rel="stylesheet" href="/css/styleGlobalNotIndex.css">
</html>

<script>
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
