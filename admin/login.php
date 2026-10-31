<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/layout.php';
if (!empty($_SESSION['admin_login'])) { requireAdmin(); redirect('/admin/admin.php'); }
$error = ''; $login = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $login = is_string($_POST['login'] ?? null) ? trim($_POST['login']) : '';
    $password = is_string($_POST['pass'] ?? null) ? $_POST['pass'] : '';
    try {
        if (rateLimit()) { http_response_code(429); header('Retry-After: 900'); $error = 'Trop de tentatives. Réessayez dans 15 minutes.'; }
        else {
            $q = database()->prepare('SELECT login, pass FROM admin WHERE login = ?'); $q->execute([mb_substr($login, 0, 100)]); $admin = $q->fetch();
            $dummy = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi';
            $valid = strlen($password) <= 1024 && password_verify($password, $admin['pass'] ?? $dummy);
            if ($admin && $valid) {
                rateLimit(false, true); session_regenerate_id(true); $_SESSION = [];
                $_SESSION['admin_login'] = $admin['login']; $_SESSION['auth_version'] = hash('sha256', $admin['pass']);
                $_SESSION['authenticated_at'] = $_SESSION['last_activity'] = $_SESSION['rotated_at'] = time();
                auditEvent('login.succeeded');
                redirect('/admin/admin.php');
            }
            rateLimit(true); auditEvent('login.failed'); $error = 'Identifiant ou mot de passe incorrect.';
        }
    } catch (Throwable $exception) { logAdminError($exception); http_response_code(503); $error = 'La connexion est momentanément indisponible. Réessayez dans quelques instants.'; }
}
pageStart('Connexion', 'references', true);
?>
<div class="auth-story"><a class="brand" href="/"><img class="brand-logo" src="/img/logo-header-white.webp" width="400" height="226" alt="APSI BTP"><span class="brand-caption">ORDONNER. PILOTER. CONSTRUIRE.</span></a><div><div class="eyebrow">VOTRE ESPACE DE GESTION</div><h1>Le savoir-faire APSI.<br>Vos projets à la une.</h1><p>Un espace simple pour faire vivre vos références et valoriser vos réalisations.</p></div><span class="auth-copyright">APSI BTP · Administration du site</span></div>
<div class="auth-panel"><div class="auth-form-wrap"><span class="auth-lock"><?= icon('lock') ?></span><div class="eyebrow">BIENVENUE</div><h2>Connectez-vous.</h2><p class="muted">Retrouvez vos références et gérez vos projets.</p><?php if ($error): ?><div class="notice error" role="alert"><?= h($error) ?></div><?php endif; ?><form method="post" action="/admin/login.php" class="stack-form"><?= csrfField() ?><label for="login">Identifiant</label><input id="login" name="login" autocomplete="username" maxlength="100" value="<?= h($login) ?>" required autofocus><label for="pass">Mot de passe</label><div class="password-field"><input id="pass" name="pass" type="password" autocomplete="current-password" required><button type="button" data-toggle-password="pass" aria-pressed="false">Afficher</button></div><button class="button primary" type="submit">Accéder à mon espace <?= icon('arrow') ?></button></form><a class="back-to-site" href="/">← Retour au site APSI</a><p class="auth-footnote"><?= icon('lock') ?>Accès réservé à l’équipe APSI</p></div></div>
<?php pageEnd(true); ?>
