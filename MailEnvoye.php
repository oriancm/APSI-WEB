

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
    header('Location: confirmation.php');
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

    <link rel="stylesheet" href="css/styleNousContacter.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:bold">
</head>
<body>

    <?php include "./nav.php"; ?>

    <main class="scrolled">

        <div style="height: 50%; display: flex; align-items: center">
            <div style="text-align: center; width: 100%">
                <svg width="100px" height="100px" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <style>.cls-1{fill:none;stroke:#000000;stroke-linecap:round;stroke-linejoin:round;stroke-width:20px;}</style>
                    </defs>
                    <g data-name="Layer 2" id="Layer_2">
                        <g data-name="E408, Success, Media, media player, multimedia" id="E408_Success_Media_media_player_multimedia">
                            <circle class="cls-1" cx="256" cy="256" r="246"/>
                            <polyline class="cls-1" points="115.54 268.77 200.67 353.9 396.46 158.1"/>
                        </g>
                    </g>
                </svg>
                <p style="margin-top: 20px">Votre message a bien été envoyé</p>
            </div>


        </div>


    </main>



</body>
<link rel="stylesheet" href="css/styleGlobalNotIndex.css">
</html>

