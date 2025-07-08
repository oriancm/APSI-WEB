

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $email = $_POST['email'];
    $subject = $_POST['sujet'];
    $message = $_POST['message'];

    $to = 'orian.cm@hotmail.com';
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link rel="stylesheet" href="css/mailSent.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:bold">
</head>
<body>

    <?php include "./nav.php"; ?>

    <main id="main" class="scrolled">   

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
<link rel="stylesheet" href="/css/styleGlobalNotIndex.css">
</html>
<script>
    addEventListener('load', (event) => {
        var navElement = document.getElementById('nav');
        var navHeight = navElement.offsetHeight;
        
        var mainElement = document.getElementById('main');
        mainElement.style.marginTop = navHeight + 'px';
    });
</script>
