<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="css/clients.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:bold">
</head>
<body>
    <?php include "./nav.php"; ?>


    <main id="main" class="scrolled">
        <section>
            <div class="content">
                <h1>Nos Clients</h1>
                <div class="logo-gallery">
                    <img src="img/logo/alpes.png" alt="Alpes Logo">
                    <img src="img/logo/chateauneuf.png" alt="Chateauneuf Logo">
                    <img src="img/logo/citadis.png" alt="Citadis Logo">
                    <img src="img/logo/coudoux.png" alt="Coudoux Logo">
                    <img src="img/logo/GrandDelta.png" alt="Grand Delta Logo">
                    <img src="img/logo/vaucluseSapeurs.png" alt="Sapeurs Pompiers du Vaucluse Logo">
                    <img src="img/logo/justice.png" alt="Justice Logo">
                    <img src="img/logo/Logo_Crous_vectorisé.svg.png" alt="Crous Logo">
                    <img src="img/logo/Logo_ville_Vitrolles.png" alt="Vitrolles Logo">
                    <img src="img/logo/Office_national_des_forêts_logo.png" alt="ONF Logo">
                    <img src="img/logo/paysdapt.jpg" alt="Pays d'Apt Logo">
                    <img src="img/logo/provenceAlpes.png" alt="Provence Alpes Logo">
                    <img src="img/logo/sdis.png" alt="SDIS Logo">
                    <img src="img/logo/soleam.png" alt="Soleam Logo">
                    <img src="img/logo/talpesdusud.svg" alt="Talpes du Sud Logo">
                    <img src="img/logo/territoire.png" alt="Territoire Logo">
                    <img src="img/logo/var.svg.png" alt="Var Logo">
                    <img src="img/logo/vaucluse.svg.png" alt="Vaucluse Logo">
                    <img src="img/logo/ville.png" alt="Ville Logo">
                </div>
            </div>
        </section>
        <div class="pb">
            <p class="slogan">APSI BTP, vos projets en toute sérénité…</p>
        </div>
    </main>



</body>
</html>
<script>
    addEventListener('load', (event) => {
        var navElement = document.getElementById('nav');
        var navHeight = navElement.offsetHeight;
        
        var mainElement = document.getElementById('main');
        mainElement.style.marginTop = navHeight + 'px';
    });
</script>
<link rel="stylesheet" href="css/styleGlobalNotIndex.css">