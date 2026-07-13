<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer6.10/src/PHPMailer.php';
require 'phpmailer6.10/src/SMTP.php';
require 'phpmailer6.10/src/Exception.php';

// Load .env file if it exists
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim(trim($value), "\"'");
        if (getenv($key) === false && !isset($_ENV[$key])) {
            $_ENV[$key] = $value;
            putenv($key . '=' . $value);
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $objet = isset($_POST['objet']) ? trim($_POST['objet']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $smtpHost = getenv('SMTP_HOST') ?: 'smtp.gmail.com';
    $smtpPort = (int)(getenv('SMTP_PORT') ?: 587);
    $smtpUsername = getenv('SMTP_USERNAME') ?: '';
    $smtpPassword = getenv('SMTP_PASSWORD') ?: '';
    $smtpFrom = getenv('SMTP_FROM') ?: $smtpUsername;
    $smtpFromName = getenv('SMTP_FROM_NAME') ?: 'Formulaire de contact';
    $smtpTo = getenv('SMTP_TO') ?: '';

    if ($name === '' || $objet === '' || $message === '') {
        $form_error = "Veuillez remplir tous les champs.";
    } elseif ($smtpUsername === '' || $smtpPassword === '' || $smtpFrom === '' || $smtpTo === '') {
        $form_error = "La configuration email du site est incomplète.";
    } else {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = $smtpHost;
            $mail->SMTPAuth = true;
            $mail->Username = $smtpUsername;
            $mail->Password = $smtpPassword;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $smtpPort;
            $mail->CharSet = 'UTF-8';
            $mail->setFrom($smtpFrom, $smtpFromName);
            $mail->addAddress($smtpTo);
            $mail->Subject = "Message de $name : $objet";
            $mail->Body = $message;
            $mail->send();
            header('Location: mailSent');
            exit;
        } catch (Exception $e) {
            $form_error = "Erreur lors de l'envoi du message.";
        }
    }
}

$activePage = 'contact';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nous Contacter - APSI BTP | Contactez-nous pour vos projets BTP</title>
    <meta name="description" content="Contactez APSI BTP pour vos projets d'Ordonnancement Pilotage Coordination (OPC) et Maîtrise d'Oeuvre d'Exécution (MOEX).">
    <meta name="keywords" content="contact APSI BTP, devis OPC, maîtrise d'oeuvre, projet BTP, Provence, PACA">
    <meta name="author" content="APSI BTP">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://apsi-btp.fr/contact">
    
    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://apsi-btp.fr/contact">
    <meta property="og:title" content="Nous Contacter - APSI BTP">
    <meta property="og:description" content="Contactez-nous pour vos projets d'Ordonnancement Pilotage Coordination (OPC) et Maîtrise d'Oeuvre d'Exécution (MOEX).">
    <meta property="og:image" content="https://apsi-btp.fr/img/APSI.avif">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Nous Contacter - APSI BTP">
    <meta name="twitter:description" content="Contactez-nous pour vos projets d'Ordonnancement Pilotage Coordination (OPC) et Maîtrise d'Oeuvre d'Exécution (MOEX).">
    <meta name="twitter:image" content="https://apsi-btp.fr/img/APSI.avif">

    <!-- Schema.org ContactPage JSON-LD -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "ContactPage",
      "name": "Nous Contacter - APSI BTP",
      "description": "Page de contact pour l'entreprise APSI BTP, spécialiste en Ordonnancement Pilotage et Coordination (OPC) et Maîtrise d'Oeuvre d'Exécution (MOEX).",
      "url": "https://apsi-btp.fr/contact",
      "mainEntity": {
        "@type": "LocalBusiness",
        "name": "APSI BTP",
        "email": "test@apsi-btp.fr",
        "address": {
          "@type": "PostalAddress",
          "addressLocality": "Caseneuve",
          "postalCode": "84750",
          "addressCountry": "FR"
        }
      }
    }
    </script>

    <link rel="icon" type="image/x-icon" href="/favicon.ico">
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
        <?= apsi_minify_css(__DIR__ . '/css/site.css'); ?>
        <?= apsi_minify_css(__DIR__ . '/css/contact.css'); ?>
    </style>
    
    <!-- Fonts Preload -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap">
    </noscript>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>
    <script src="/js/site.js" defer></script>
</head>
<body class="contact-body">
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-container">
            <div class="loading-spinner"></div>
            <div class="loading-text">Envoi en cours...</div>
        </div>
    </div>

    <main id="main" class="contact-page hidden-until-loaded">
        <?php include __DIR__ . '/partials/siteHeader.php'; ?>

        <section class="contact-hero site-narrow-container">
            <div class="contact-copy">
                <h1>Nous Contacter</h1>
                <p>
                    Une question, un projet ?<br>
                    Notre équipe est à <strong>votre écoute</strong> pour<br>
                    vous accompagner dans la <strong>réussite<br>
                    de vos opérations.</strong>
                </p>
            </div>

            <form action="/contact" method="POST" id="contactForm" class="contact-form">
                <?php if (isset($form_error)) : ?>
                    <p class="form-error"><?php echo htmlspecialchars($form_error); ?></p>
                <?php endif; ?>

                <label for="fname">Nom Prénom</label>
                <div class="field">
                    <i data-lucide="user-round" aria-hidden="true"></i>
                    <input type="text" id="fname" name="name" placeholder="Ex : MARTY Paul" value="<?php echo isset($name) ? htmlspecialchars($name) : '' ?>">
                </div>

                <label for="sujet">Objet</label>
                <div class="field">
                    <i data-lucide="clipboard-list" aria-hidden="true"></i>
                    <input type="text" id="sujet" name="objet" placeholder="Ex : Demande d'information" value="<?php echo isset($objet) ? htmlspecialchars($objet) : '' ?>">
                </div>

                <label for="subject">Message</label>
                <textarea id="subject" name="message" placeholder="Votre message..."><?php echo isset($message) ? htmlspecialchars($message) : '' ?></textarea>

                <div class="contact-form-footer">
                    <a href="mailto:test@apsi-btp.fr" class="mail-link">
                        <i data-lucide="mail" aria-hidden="true"></i>
                        test@apsi-btp.fr
                    </a>
                    <button type="submit" id="submitBtn">
                        Envoyer
                        <i data-lucide="send" aria-hidden="true"></i>
                    </button>
                </div>
            </form>
        </section>
    </main>

    <?php include __DIR__ . '/partials/siteFooter.php'; ?>

    <script>
        window.addEventListener('load', function () {
            var navElement = document.getElementById('nav');
            var mainElement = document.getElementById('main');
            if (mainElement) {
                mainElement.classList.remove('hidden-until-loaded');
                mainElement.classList.add('show-after-load');
            }
            if (navElement) {
                navElement.classList.remove('hidden-until-loaded');
                navElement.classList.add('show-after-load');
            }
            if (window.lucide) window.lucide.createIcons();
        });

        const contactForm = document.getElementById('contactForm');
        const loadingOverlay = document.getElementById('loadingOverlay');
        const submitBtn = document.getElementById('submitBtn');

        contactForm.addEventListener('submit', function () {
            loadingOverlay.style.display = 'flex';
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Envoi...';
        });

        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                loadingOverlay.style.display = 'none';
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Envoyer <i data-lucide="send" aria-hidden="true"></i>';
                if (window.lucide) window.lucide.createIcons();
            }
        });
    </script>
</body>
</html>
