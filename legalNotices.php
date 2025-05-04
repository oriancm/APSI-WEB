<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>APSI BTP - Mentions Légales</title>
    <link rel="stylesheet" href="css/styleMentionsLegales.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:bold">
</head>
<body>

    <?php include "./nav.php"; ?>

    <main id="main" class="scrolled">
        <section>
            <div class="content">
                <h1>Mentions légales</h1>
                
                <div class="legal-section">
                    <h2>Éditeur du site</h2>
                    <p>Le présent site est édité par : <strong>[Nom de l'entreprise]</strong>, société de type <strong>[forme juridique]</strong>, au capital de <strong>[montant du capital social]</strong>, ayant son siège social situé à <strong>[adresse du siège social]</strong>, immatriculée au Registre du Commerce et des Sociétés de <strong>[ville]</strong> sous le numéro <strong>[numéro RCS]</strong>.</p>
                    <p>Numéro de TVA intracommunautaire : <strong>[à compléter si applicable]</strong></p>
                    <p>Directeur de la publication : <strong>[Nom du dirigeant]</strong></p>
                    <p>Contact : <strong>[adresse e-mail ou numéro de téléphone]</strong></p>
                </div>
                
                <div class="legal-section">
                    <h2>Hébergement</h2>
                    <p>Le site est hébergé par : <strong>[Nom de l'hébergeur]</strong></p>
                    <p>Adresse : <strong>[adresse complète de l'hébergeur]</strong></p>
                    <p>Site web : <strong>[URL du site de l'hébergeur]</strong></p>
                    <p>Téléphone : <strong>[numéro de l'hébergeur]</strong></p>
                </div>
                
                <p class="slogan">APSI BTP, vos projets en toute sérénité…</p>
            </div>
        </section>
    </main>


</body>
<link rel="stylesheet" href="css/styleGlobalNotIndex.css">
</html>

<script>
    addEventListener('load', (event) => {
        var navElement = document.getElementById('nav');
        var navHeight = navElement.offsetHeight;
        
        var mainElement = document.getElementById('main');
        mainElement.style.marginTop = navHeight + 'px';
    });
</script>