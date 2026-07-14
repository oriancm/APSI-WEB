

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $email = $_POST['email'];
    $subject = $_POST['sujet'];
    $message = $_POST['message'];

    $to = 'test@apsi-btp.fr';
    $subject = 'Nouveau message de ' . $firstname . ': ' . $subject;
    $headers = 'From: ' . $email . "\r\n" .
        'Reply-To: ' . $email . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    // Envoi de l'email
    mail($to, $subject, $message, $headers);

    // Redirection vers une page de confirmation
    // header('Location: confirmation.php');
    exit;
}
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Message Envoyé - APSI BTP</title>
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="refresh" content="5;url=index.php">
    <script>
        setTimeout(function() {
            window.location.href = 'index.php';
        }, 5000);
    </script>
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
        <?= apsi_minify_css(__DIR__ . '/css/styleGlobalNotIndex.css'); ?>
    </style>
    <link rel="preload" href="css/mailSent.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <!-- Fonts Preload -->
    <link rel="preload" href="https://fonts.googleapis.com/css?family=Montserrat:bold" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <noscript>
        <link rel="stylesheet" href="css/mailSent.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:bold">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    </noscript>
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

        <div">
            <div style="text-align: center; width: 100%">
            <!-- <h1 class="senth1">Nous Contacter</h1> -->
            <div class="sent">
            <svg width="100px" height="100px" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <style>.cls-1{fill:none;stroke:dodgerblue;stroke-linecap:round;stroke-linejoin:round;stroke-width:20px;}</style>
                </defs>
                <g data-name="Layer 2" id="Layer_2">
                    <g data-name="E408, Success, Media, media player, multimedia" id="E408_Success_Media_media_player_multimedia">
                        <circle class="cls-1" cx="256" cy="256" r="246"/>
                        <polyline class="cls-1" points="115.54 268.77 200.67 353.9 396.46 158.1"/>
                    </g>
                </g>
            </svg>
                <h1 class="senth1">Message envoyé !</h1>
                <p style="margin-top: 20px">Merci pour votre message ! Nous avons bien reçu votre demande et nous vous répondrons dans les plus brefs délais.</p>
            </div>
                
            </div>
        </div>
        <div class="pb">
            <p class="slogan">APSI BTP, vos projets en toute sérénité…</p>
        </div>
    </main>



</body>
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
        <?= apsi_minify_css(__DIR__ . '/css/styleGlobalNotIndex.css'); ?>
    </style>

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
