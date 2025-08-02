<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- SEO Meta Tags -->
    <title>Nos Clients - APSI BTP | Partenaires et Maîtres d'Ouvrage</title>
    <meta name="description" content="Découvrez nos clients et partenaires : collectivités, institutions publiques et privées qui nous font confiance pour leurs projets BTP en Provence-Alpes-Côte d'Azur.">
    <meta name="keywords" content="clients APSI BTP, partenaires, maîtres d'ouvrage, collectivités, institutions, Provence, PACA">
    <meta name="author" content="APSI BTP">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://apsi-btp.fr/clients">
    <meta property="og:title" content="Nos Clients - APSI BTP">
    <meta property="og:description" content="Découvrez nos clients et partenaires qui nous font confiance pour leurs projets BTP.">
    <meta property="og:image" content="/img/APSI.png">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://apsi-btp.fr/clients">
    <meta property="twitter:title" content="Nos Clients - APSI BTP">
    <meta property="twitter:description" content="Découvrez nos clients et partenaires qui nous font confiance pour leurs projets BTP.">
    <meta property="twitter:image" content="/img/APSI.png">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="manifest" href="/site.webmanifest">
    
    <link rel="stylesheet" href="/css/clients.css">
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
                <h1>Nos Clients</h1>
                <div class="logo-gallery">
                    <img src="/img/logo/alpes.png" alt="Alpes Logo">
                    <a href="https://www.chateauneuflesmartigues.fr/" target="_blank" rel="noopener"><img src="/img/logo/chateauneuf.png" alt="Chateauneuf Logo" class="tropGrand"></a>
                    <a href="https://citadis.fr/" target="_blank" rel="noopener"><img src="/img/logo/citadis.png" alt="Citadis Logo"></a>
                    <a href="https://www.coudoux.fr/" target="_blank" rel="noopener"><img src="/img/logo/coudoux.png" alt="Coudoux Logo" class="tropGrand"></a>
                    <a href="https://www.granddelta.fr/" target="_blank" rel="noopener"><img src="/img/logo/GrandDelta.png" alt="Grand Delta Logo" class="tropGrand"></a>
                    <a href="https://www.sdis84.fr/" target="_blank" rel="noopener"><img src="/img/logo/vaucluseSapeurs.png" alt="Sapeurs Pompiers du Vaucluse Logo"></a>
                    <a href="https://www.justice.gouv.fr/" target="_blank" rel="noopener"><img src="/img/logo/justice.png" alt="Justice Logo"></a>
                    <a href="https://www.lescrous.fr/" target="_blank" rel="noopener"><img src="/img/logo/Logo_Crous_vectorisé.svg.png" alt="Crous Logo"></a>
                    <img src="/img/logo/Logo_ville_Vitrolles.png" alt="Vitrolles Logo">
                    <a href="https://www.onf.fr/" target="_blank" rel="noopener"><img src="/img/logo/Office_national_des_forêts_logo.png" alt="ONF Logo"></a>
                    <a href="https://www.paysapt-luberon.fr/" target="_blank" rel="noopener"><img src="/img/logo/paysdapt.jpg" alt="Pays d'Apt Logo" class="tropGrand"></a>
                    <a href="https://www.provencealpesagglo.fr/" target="_blank" rel="noopener"><img src="/img/logo/provenceAlpes.png" alt="Provence Alpes Logo"></a>
                    <a href="https://www.sdis04.fr/" target="_blank" rel="noopener"><img src="/img/logo/sdis.png" alt="SDIS Logo"></a>
                    <a href="https://www.soleam.net/" target="_blank" rel="noopener"><img src="/img/logo/soleam.png" alt="Soleam Logo"></a>
                    <img src="/img/logo/talpesdusud.svg" alt="Talpes du Sud Logo">
                    <a href="https://splterritoire84.com/fr" target="_blank" rel="noopener"><img src="/img/logo/territoire.png" alt="Territoire Logo"></a>
                    <a href="https://var.fr/" target="_blank" rel="noopener"><img src="/img/logo/var.svg.png" alt="Var Logo"></a>
                    <a href="https://www.vaucluse.fr/accueil-3.html" target="_blank" rel="noopener"><img src="/img/logo/vaucluse.svg.png" alt="Vaucluse Logo"></a>
                    <a href="https://www.apt.fr/" target="_blank" rel="noopener"><img src="/img/logo/ville.png" alt="Ville Logo" class="tropGrand"></a>
                </div>
            </div>
            <div class="pb">
                <p class="slogan">APSI BTP, vos projets en toute sérénité…</p>
            </div>
        </section>
        
    </main>



</body>
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
<link rel="stylesheet" href="/css/styleGlobalNotIndex.css">