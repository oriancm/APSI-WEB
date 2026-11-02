<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/phpmailer6.10/src/PHPMailer.php';
require __DIR__ . '/phpmailer6.10/src/SMTP.php';
require __DIR__ . '/phpmailer6.10/src/Exception.php';

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
    $field = static fn(string $key): string => is_string($_POST[$key] ?? null) ? trim($_POST[$key]) : '';
    $name = $field('name');
    $email = $field('email');
    $objet = $field('objet');
    $message = $field('message');
    $smtpHost = getenv('SMTP_HOST') ?: '';
    $smtpPort = (int)(getenv('SMTP_PORT') ?: 587);
    $smtpUsername = getenv('SMTP_USERNAME') ?: '';
    $smtpPassword = getenv('SMTP_PASSWORD') ?: '';
    $smtpFrom = getenv('SMTP_FROM') ?: ($smtpUsername ?: 'info@apsi-btp.fr');
    $smtpFromName = getenv('SMTP_FROM_NAME') ?: 'APSI BTP — Formulaire de contact';
    // All website contact requests go to the company's contact inbox.
    $smtpTo = 'info@apsi-btp.fr';

    if ($name === '' || $email === '' || $objet === '' || $message === '') {
        $form_error = "Veuillez remplir tous les champs.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || preg_match('/[\r\n]/', $email . $name . $objet)) {
        $form_error = "Veuillez vérifier votre adresse e-mail, votre nom et l'objet du message.";
    } elseif (strlen($name) > 200 || strlen($email) > 254 || strlen($objet) > 300 || strlen($message) > 20000) {
        $form_error = "Votre message est trop long. Veuillez raccourcir les champs saisis.";
    } elseif ($field('website') !== '') {
        $form_error = "Impossible d'envoyer ce formulaire.";
    } else {
        $mail = new PHPMailer(true);
        try {
            if ($smtpHost !== '') {
                $mail->isSMTP();
                $mail->Host = $smtpHost;
                $mail->SMTPAuth = $smtpUsername !== '' || $smtpPassword !== '';
                $mail->Username = $smtpUsername;
                $mail->Password = $smtpPassword;
                $encryption = getenv('SMTP_ENCRYPTION') ?: ($smtpPort === 465 ? 'ssl' : 'tls');
                if (!in_array($encryption, ['ssl', 'tls', 'none'], true)) {
                    throw new Exception('Invalid SMTP_ENCRYPTION configuration');
                }
                $mail->SMTPSecure = $encryption === 'none' ? '' : $encryption;
                $mail->SMTPAutoTLS = $encryption !== 'none';
                $mail->Port = $smtpPort;
                $mail->Timeout = 15;
            } else {
                // OVH shared hosting provides PHP mail without Gmail credentials.
                $mail->isMail();
            }
            $mail->CharSet = 'UTF-8';
            $mail->setFrom($smtpFrom, $smtpFromName);
            $mail->addAddress($smtpTo);
            $mail->addReplyTo($email, $name);
            $mail->Subject = "Message de $name : $objet";
            $mail->Body = "Nouvelle demande depuis le site APSI BTP\n\nNom : $name\nE-mail : $email\nObjet : $objet\n\n$message";
            $mail->send();
            header('Location: /mailSent', true, 303);
            exit;
        } catch (Exception $e) {
            error_log('APSI contact mail delivery failed: ' . $e->getMessage());
            $form_error = "L'envoi a échoué. Veuillez réessayer ou nous écrire à info@apsi-btp.fr.";
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
    <?php require __DIR__ . '/partials/favicons.php'; ?>
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
                <img width="2172" height="724" class="contact-scene__image" src="<?= apsiAsset('/img/home-crane-original.webp') ?>" alt="Grue et bâtiment en construction" decoding="async">
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
                    <input type="text" id="fname" name="name" autocomplete="name" maxlength="200" required placeholder="Ex : MARTY Paul" value="<?php echo isset($name) ? htmlspecialchars($name) : '' ?>">
                </div>
                </div>
                <div class="contact-form-group contact-form-group--email">
                <label for="email">Adresse e-mail</label>
                <div class="field">
                    <i data-lucide="mail" aria-hidden="true"></i>
                    <input type="email" id="email" name="email" autocomplete="email" maxlength="254" required placeholder="Ex : nom@entreprise.fr" value="<?= isset($email) ? htmlspecialchars($email, ENT_QUOTES, 'UTF-8') : '' ?>">
                </div>
                </div>
                <div class="contact-form-group contact-form-group--subject">
                <label for="sujet">Objet</label>
                <div class="field">
                    <i data-lucide="clipboard-list" aria-hidden="true"></i>
                    <input type="text" id="sujet" name="objet" maxlength="300" required placeholder="Ex : Demande d'information" value="<?php echo isset($objet) ? htmlspecialchars($objet) : '' ?>">
                </div>
                </div>
                <div class="contact-form-trap" aria-hidden="true" inert>
                    <label for="website">Site internet</label>
                    <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                </div>
                <div class="contact-form-group contact-form-group--message">
                <label for="subject">Message</label>
                <div class="contact-message-row">
                <textarea id="subject" name="message" maxlength="20000" required placeholder="Votre message..."><?php echo isset($message) ? htmlspecialchars($message) : '' ?></textarea>
                    <button type="submit" id="submitBtn" aria-label="Envoyer le message" title="Envoyer le message">
                        <i data-lucide="send" aria-hidden="true"></i>
                    </button>
                </div>
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
            submitBtn.setAttribute('aria-busy', 'true');
        });

        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                loadingOverlay.style.display = 'none';
                submitBtn.disabled = false;
                submitBtn.removeAttribute('aria-busy');
            }
        }, { signal: window.apsiPageSignal });
    </script>
</body>
</html>
