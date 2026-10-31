<?php
require_once __DIR__ . '/bootstrap.php';
$admin = requireAdmin();
require_once __DIR__ . '/layout.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $current = is_string($_POST['current_password'] ?? null) ? $_POST['current_password'] : '';
    $password = is_string($_POST['new_password'] ?? null) ? $_POST['new_password'] : '';
    $confirm = is_string($_POST['confirm_password'] ?? null) ? $_POST['confirm_password'] : '';
    try {
        if (rateLimit()) { http_response_code(429); $error = 'Trop de tentatives. Réessayez dans 15 minutes.'; }
        elseif (!password_verify($current, $admin['pass'])) { rateLimit(true); $error = 'Le mot de passe actuel est incorrect.'; }
        elseif (strlen($password) < 12 || strlen($password) > 72) $error = 'Choisissez un mot de passe de 12 à 72 caractères (sans dépasser 72 octets).';
        elseif ($password !== $confirm) $error = 'Les deux nouveaux mots de passe ne correspondent pas.';
        elseif (password_verify($password, $admin['pass'])) $error = 'Choisissez un mot de passe différent du mot de passe actuel.';
        else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $q = database()->prepare('UPDATE admin SET pass = ? WHERE login = ? AND pass = ?'); $q->execute([$hash, $admin['login'], $admin['pass']]);
            if ($q->rowCount() !== 1) throw new RuntimeException('Compte modifié simultanément.');
            session_regenerate_id(true); $_SESSION['auth_version'] = hash('sha256', $hash); $_SESSION['csrf'] = bin2hex(random_bytes(32));
            auditEvent('password.changed');
            rateLimit(false, true); flash('Votre mot de passe a été modifié. Les autres sessions ont été déconnectées.');
            redirect('/admin/change_password.php');
        }
    } catch (Throwable $exception) { logAdminError($exception); $error = 'Le mot de passe n’a pas pu être modifié. Réessayez.'; }
}
pageStart('Sécurité du compte', 'settings');
?>
<div class="page-heading"><div><div class="eyebrow">VOTRE COMPTE</div><h1>Sécurité du compte<span class="heading-dot">.</span></h1><p>Protégez l’accès aux références de votre entreprise.</p></div></div><section class="panel security-panel"><div class="panel-heading"><span class="section-number"><?= icon('lock') ?></span><div><h2>Changer le mot de passe</h2><p>Compte connecté : <?= h($admin['login']) ?></p></div></div><?php if ($error): ?><div class="notice error" role="alert"><?= h($error) ?></div><?php endif; ?><form method="post" class="stack-form"><?= csrfField() ?><label for="current_password">Mot de passe actuel</label><input type="password" id="current_password" name="current_password" autocomplete="current-password" required><label for="new_password">Nouveau mot de passe</label><input type="password" id="new_password" name="new_password" autocomplete="new-password" minlength="12" maxlength="72" required aria-describedby="password-help"><p id="password-help" class="field-help">Au moins 12 caractères. Une phrase de plusieurs mots est facile à retenir.</p><label for="confirm_password">Confirmer le nouveau mot de passe</label><input type="password" id="confirm_password" name="confirm_password" autocomplete="new-password" minlength="12" maxlength="72" required><button type="submit" class="button primary">Mettre à jour le mot de passe</button></form></section>
<?php pageEnd(); ?>
