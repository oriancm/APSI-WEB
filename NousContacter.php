

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = 'oriancm@hotmail.com'; // sender
    $subject = $_POST['objet'];
    $message = $_POST['message'];

    $to = 'orian.cm@hotmail.com';
    $subject = 'Nouveau message de ' . $firstname . ': ' . $subject;
    $headers = 'From: ' . $email . "\r\n" .
        'Reply-To: ' . $email . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    // Envoi de l'email
    $mail_result = mail($to, $subject, $message, $headers);

    // Vérification de l'envoi de l'e-mail
    if ($mail_result) {
        // Redirection vers une page de confirmation si l'e-mail est envoyé avec succès
        header('Location: MailEnvoye.php');
        exit;
    } else {
        // Afficher un message d'erreur si l'envoi de l'e-mail échoue
        echo "Erreur lors de l'envoi de l'e-mail.";
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

    <link rel="stylesheet" href="css/styleNousContacter.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:bold">
</head>
<body>

    <?php include "./nav.php"; ?>

    <main class="scrolled">

        <!--
Question :
https://openclassrooms.com/forum/sujet/formulaire-de-contact-responsive
-->
        <div class="container">
            <h1>Formulaire de contact</h1>
            <form action="NousContacter.php" method="POST">
                <label for="fname">NOM Prénom</label>
                <input type="text" id="fname" name="name" placeholder="ex: VALERY Paul">

                <label for="sujet">Objet</label>
                <input type="text" id="sujet" name="objet" placeholder="L'objet de votre message">

                <label for="subject">Message</label>
                <textarea id="subject" name="message" placeholder="Votre message" style="height:200px"></textarea>

                <input type="submit" value="Envoyer">
            </form>
        </div>


    </main>



</body>
<link rel="stylesheet" href="css/styleGlobalNotIndex.css">
</html>

