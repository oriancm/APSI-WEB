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
                    <p>Le présent site est édité par : <strong>APSI BTP</strong>, société de type <strong>SARL</strong>, au capital de <strong>6 800,00 €</strong>, ayant son siège social situé à <strong>QUARTIER PIERREFEU, CHEMIN DE SAINT JEAN, 84750 CASENEUVE</strong>, immatriculée au Registre du Commerce et des Sociétés d'<strong>Avignon</strong> sous le numéro <strong>495 392 557 R.C.S. Avignon</strong>.</p>
                    <p>Numéro de TVA intracommunautaire : <strong>FR40495392557</strong></p>
                    <p>Directeur de la publication : <strong>Ludovic MESSY</strong></p>
                    <p>Contact : <strong>l.messy@apsi-btp.fr</strong></p>
                </div>
                
                <div class="legal-section">
                    <h2>Hébergement</h2>
                    <p>Le site est hébergé par : <strong>OVH</strong></p>
                    <p>Adresse : <strong>2 rue Kellermann, 59100 Roubaix, France</strong></p>
                    <p>Site web : <strong><a href="https://www.ovh.com" target="_blank">https://www.ovh.com</a></strong></p>
                    <p>Téléphone : <strong>1007</strong> (gratuit depuis un poste fixe en France)</p>
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
