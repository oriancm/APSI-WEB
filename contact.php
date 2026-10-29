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
    <meta property="og:image" content="https://apsi-btp.fr/img/logo-nav.webp">
    <meta property="og:image:type" content="image/webp">
    <meta property="og:image:width" content="1146">
    <meta property="og:image:height" content="652">
    <meta property="og:image:alt" content="APSI BTP - Coordination de travaux">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Nous Contacter - APSI BTP">
    <meta name="twitter:description" content="Contactez-nous pour vos projets d'Ordonnancement Pilotage Coordination (OPC) et Maîtrise d'Oeuvre d'Exécution (MOEX).">
    <meta name="twitter:image" content="https://apsi-btp.fr/img/logo-nav.webp">
    <meta name="twitter:image:alt" content="APSI BTP - Coordination de travaux">

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
        "logo": "https://apsi-btp.fr/img/logo-nav.webp",
        "image": "https://apsi-btp.fr/img/logo-nav.webp",
        "email": "info@apsi-btp.fr",
        "address": {
          "@type": "PostalAddress",
          "addressLocality": "Caseneuve",
          "postalCode": "84750",
          "addressCountry": "FR"
        }
      }
    }
    </script>

    <!-- Favicons & Manifest -->
    <link rel="icon" type="image/png" sizes="48x48" href="/favicon-48x48.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/android-chrome-192x192.png">
    <link rel="icon" type="image/png" sizes="512x512" href="/android-chrome-512x512.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="manifest" href="/site.webmanifest">
    <!-- Fonts Preconnect -->

        <!-- Stylesheets (Inlined dynamically via PHP to prevent FOUC & maximize mobile performance) -->
        <?php require_once __DIR__ . '/functions/assets.php'; ?>
    <link data-apsi-style rel="stylesheet" href="<?= apsiAsset('/css/site.css') ?>">
    <link data-apsi-style rel="stylesheet" href="<?= apsiAsset('/css/contact.css') ?>">
    
    <!-- Fonts Preload -->
    <link rel="stylesheet" href="<?= apsiAsset('/css/fonts.css') ?>">
    <script src="<?= apsiAsset('/js/icons.min.js') ?>" defer></script>
    <script src="<?= apsiAsset('/js/navigation.js') ?>"></script>
    <script src="<?= apsiAsset('/js/site.js') ?>" defer></script>
    <?php include __DIR__ . '/partials/seo.php'; ?>
</head>
<body class="contact-body">
    <a class="skip-link" href="#main">Aller au contenu principal</a>
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-container">
            <div class="loading-spinner"></div>
            <div class="loading-text">Envoi en cours...</div>
        </div>
    </div>

    <main id="main" tabindex="-1" class="contact-page hidden-until-loaded">
        <?php include __DIR__ . '/partials/siteHeader.php'; ?>

        <section class="contact-hero" aria-labelledby="contact-title">
            <div class="contact-scene">
                <img width="1200" height="400" class="contact-scene__image" src="/img/contact-crane.webp" alt="Grue et bâtiment en construction">
                <div class="contact-details">
                    <a href="mailto:info@apsi-btp.fr"><i data-lucide="mail" aria-hidden="true"></i><strong>info@apsi-btp.fr</strong></a>
                    <p><i data-lucide="map-pin" aria-hidden="true"></i><span>84750 Caseneuve</span></p>
                </div>
            </div>
            <div class="contact-content">
            <div class="contact-copy">
                <p class="contact-eyebrow">Nous contacter</p>
                <h1 id="contact-title">Un projet ?<br><span>Échangeons ensemble.</span></h1>
                <p class="contact-description">Une question, un projet ? Notre équipe est à <strong>votre écoute</strong> pour vous accompagner dans la <strong>réussite de vos opérations.</strong></p>
            </div>

            <form action="/contact" method="POST" id="contactForm" class="contact-form">
                <?php if (isset($form_error)) : ?>
                    <p class="form-error" role="alert"><?php echo htmlspecialchars($form_error); ?></p>
                <?php endif; ?>

                <div class="contact-form-group">
                <label for="fname">Nom Prénom</label>
                <div class="field">
                    <i data-lucide="user-round" aria-hidden="true"></i>
                    <input type="text" id="fname" name="name" autocomplete="name" required placeholder="Ex : MARTY Paul" value="<?php echo isset($name) ? htmlspecialchars($name) : '' ?>">
                </div>
                </div>
                <div class="contact-form-group">
                <label for="sujet">Objet</label>
                <div class="field">
                    <i data-lucide="clipboard-list" aria-hidden="true"></i>
                    <input type="text" id="sujet" name="objet" required placeholder="Ex : Demande d'information" value="<?php echo isset($objet) ? htmlspecialchars($objet) : '' ?>">
                </div>
                </div>
                <div class="contact-form-group contact-form-group--message">
                <label for="subject">Message</label>
                <textarea id="subject" name="message" required placeholder="Votre message..."><?php echo isset($message) ? htmlspecialchars($message) : '' ?></textarea>
                </div>
                <div class="contact-form-footer">
                    <button type="submit" id="submitBtn">
                        Envoyer
                        <i data-lucide="send" aria-hidden="true"></i>
                    </button>
                    <a href="/privacyPolicy" class="contact-privacy-link">Politique de confidentialité <i data-lucide="arrow-right" aria-hidden="true"></i></a>
                </div>
            </form>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/partials/siteFooter.php'; ?>

    <script>
        window.apsiPageReady(function () {
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
        }, { signal: window.apsiPageSignal });
    </script>
</body>
</html>
