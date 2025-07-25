<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer6.10/src/PHPMailer.php';
require 'phpmailer6.10/src/SMTP.php';
require 'phpmailer6.10/src/Exception.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $objet = isset($_POST['objet']) ? trim($_POST['objet']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    if ($name === '' || $objet === '' || $message === '') {
        $form_error = "Veuillez remplir tous les champs.";
    } else {
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
            header('Location: mailSent');
            exit;

        } catch (Exception $e) {
            echo "Erreur : {$mail->ErrorInfo}";
        }
    }
}
?>


<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- SEO Meta Tags -->
    <title>Nous Contacter - APSI BTP | Contactez-nous pour vos projets BTP</title>
    <meta name="description" content="Contactez APSI BTP pour vos projets d'Ordonnancement Pilotage Coordination (OPC) et Maîtrise d'Œuvre d'Exécution (MOEX) en Provence-Alpes-Côte d'Azur.">
    <meta name="keywords" content="contact APSI BTP, devis OPC, maîtrise d'œuvre, projet BTP, Provence, PACA">
    <meta name="author" content="APSI BTP">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://apsi-btp.fr/contact">
    <meta property="og:title" content="Nous Contacter - APSI BTP">
    <meta property="og:description" content="Contactez-nous pour vos projets d'Ordonnancement Pilotage Coordination (OPC) et Maîtrise d'Œuvre d'Exécution (MOEX).">
    <meta property="og:image" content="/img/APSI.png">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://apsi-btp.fr/contact">
    <meta property="twitter:title" content="Nous Contacter - APSI BTP">
    <meta property="twitter:description" content="Contactez-nous pour vos projets d'Ordonnancement Pilotage Coordination (OPC) et Maîtrise d'Œuvre d'Exécution (MOEX).">
    <meta property="twitter:image" content="/img/APSI.png">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="manifest" href="/site.webmanifest">

    <link rel="stylesheet" href="css/contact.css">
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

    <!-- Loading overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-container">
            <div class="loading-spinner"></div>
            <div class="loading-text">Envoi en cours...</div>
        </div>
    </div>

    <main id="main" class="scrolled hidden-until-loaded">

    <section>
        <div class="container">
            <h1>Nous Contacter</h1>
            <div class="contact">
            <?php if (isset($form_error)) { echo '<div style="color:red; margin-bottom:10px;">' . htmlspecialchars($form_error) . '</div>'; } ?>
            <form action="contact.php" method="POST" id="contactForm">
                <label for="fname">NOM Prénom</label>
                <input type="text" id="fname" name="name" placeholder="ex: VALERY Paul" value="<?php echo isset($name) ? htmlspecialchars($name) : '' ?>">

                <label for="sujet">Objet</label>
                <input type="text" id="sujet" name="objet" placeholder="L'objet de votre message" value="<?php echo isset($objet) ? htmlspecialchars($objet) : '' ?>">

                <label for="subject">Message</label>
                <textarea id="subject" name="message" placeholder="Votre message" style="height:140px"><?php echo isset($message) ? htmlspecialchars($message) : '' ?></textarea>

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