<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer6.10/src/PHPMailer.php';
require 'phpmailer6.10/src/SMTP.php';
require 'phpmailer6.10/src/Exception.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $objet = $_POST['objet'];
    $message = $_POST['message'];

    $mail = new PHPMailer(true);

    try {
        // Config SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tempmailabc10@gmail.com'; // Email Gmail
        $mail->Password = 'vvhvegaqxoqsodwj';         // Mot de passe d'application
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Infos email
        $mail->setFrom('tempmailabc10@gmail.com', 'Formulaire de contact');
        $mail->addAddress('nairodroid@gmail.com'); // Destinataire
        $mail->Subject = "Message de $name : $objet";
        $mail->Body = $message;

        $mail->send();
        header('Location: /MailSent');
        exit;

    } catch (Exception $e) {
        echo "Erreur : {$mail->ErrorInfo}";
    }
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

    <link rel="stylesheet" href="css/contact.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:bold">
</head>
<body>

    <?php include "./nav.php"; ?>

    <!-- Loading overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-container">
            <div class="loading-spinner"></div>
            <div class="loading-text">Envoi en cours...</div>
        </div>
    </div>

    <main id="main" class="scrolled">

    <section>
        <div class="container">
            <h1>Nous Contacter</h1>
            <div class="contact">
            <form action="contact.php" method="POST" id="contactForm">
                <label for="fname">NOM Prénom</label>
                <input type="text" id="fname" name="name" placeholder="ex: VALERY Paul">

                <label for="sujet">Objet</label>
                <input type="text" id="sujet" name="objet" placeholder="L'objet de votre message">

                <label for="subject">Message</label>
                <textarea id="subject" name="message" placeholder="Votre message" style="height:140px"></textarea>

                <div class="form-footer">
                    <div class="email-contact">l.messy@apsi-btp.fr</div>
                    <input type="submit" value="Envoyer" id="submitBtn">
                </div>
            </form>
            </div>
            
        </div>
        <div class="pb">
            <p class="slogan">APSI BTP, vos projets en toute sérénité…</p>
        </div>
    </section>

    </main>

    <script>
        addEventListener('load', (event) => {
            var navElement = document.getElementById('nav');
            var navHeight = navElement.offsetHeight;
            
            var mainElement = document.getElementById('main');
            mainElement.style.marginTop = navHeight + 'px';
        });

        // Gestion du loading
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            // Afficher le loading
            document.getElementById('loadingOverlay').style.display = 'flex';
            
            // Désactiver le bouton pour éviter les double-clics
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.value = 'Envoi...';
            
            // Le formulaire continue son envoi normal
            // Le loading sera caché automatiquement lors de la redirection
        });

        // Cacher le loading si on revient sur la page (bouton retour du navigateur)
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                document.getElementById('loadingOverlay').style.display = 'none';
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.disabled = false;
                submitBtn.value = 'Envoyer';
            }
        });
    </script>
    <link rel="stylesheet" href="/css/styleGlobalNotIndex.css">
</body>
</html>