

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = 'tempmailabc10@gmail.com'; // Expéditeur
    $subject = $_POST['objet'];
    $message = $_POST['message'];

    $to = 'nairodroid@gmail.com'; // Destinataire
    $subject = 'Nouveau message de ' . $name . ': ' . $subject;
    $headers = 'From: ' . $email . "\r\n" .
        'Reply-To: ' . $email . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    // Envoi de l'email
    $mail_result = mail($to, $subject, $message, $headers);

    // Vérification de l'envoi de l'e-mail
    if ($mail_result) {
        // Redirection vers une page de confirmation si l'e-mail est envoyé avec succès
        header('Location: MailSent');
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

    <main id="main" class="scrolled">

    <section>
        <div class="container">
            <h1>Nous Contacter</h1>
            <div class="contact">
            <form action="contact.php" method="POST">
                <label for="fname">NOM Prénom</label>
                <input type="text" id="fname" name="name" placeholder="ex: VALERY Paul">

                <label for="sujet">Objet</label>
                <input type="text" id="sujet" name="objet" placeholder="L'objet de votre message">

                <label for="subject">Message</label>
                <textarea id="subject" name="message" placeholder="Votre message" style="height:150px"></textarea>

                <input type="submit" value="Envoyer">
            </form>
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
    addEventListener('load', (event) => {
        var navElement = document.getElementById('nav');
        var navHeight = navElement.offsetHeight;
        
        var mainElement = document.getElementById('main');
        mainElement.style.marginTop = navHeight + 'px';
    });
</script>
<link rel="stylesheet" href="css/styleGlobalNotIndex.css">