<?php
require_once __DIR__ . '/bootstrap.php';

function icon(string $name): string {
    $paths = [
        'grid' => '<rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>',
        'plus' => '<path d="M12 5v14M5 12h14"/>',
        'arrow' => '<path d="M7 17 17 7M7 7h10v10"/>',
        'search' => '<circle cx="10.5" cy="10.5" r="6.5"/><path d="m16 16 5 5"/>',
        'lock' => '<rect x="5" y="10" width="14" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3m-4 5v2"/>',
        'logout' => '<path d="M9 4H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h4m6-4 4-4-4-4m-6 4h10"/>',
        'photo' => '<rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8" cy="8" r="1.5"/><path d="m21 15-5-5L6 21"/>',
        'check' => '<path d="m5 12 4 4L19 6"/>',
        'back' => '<path d="m10 5-7 7 7 7M3 12h18"/>',
        'edit' => '<path d="m15 5 4 4M4 20l4-1L21 6a2.8 2.8 0 0 0-4-4L4 15l-1 6Z"/>',
        'trash' => '<path d="M3 6h18M9 6V3h6v3M5 6l1 15h12l1-15M10 10v7m4-7v7"/>',
    ];
    return '<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . ($paths[$name] ?? $paths['grid']) . '</svg>';
}
function pageStart(string $title, string $active = 'references', bool $guest = false): void {
    $version = (string)filemtime(__DIR__ . '/../css/backoffice.css');
    ?><!doctype html><html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?= h($title) ?> · APSI</title><link rel="stylesheet" href="/css/backoffice.css?v=<?= h($version) ?>"><script src="/js/backoffice.js?v=<?= h((string)filemtime(__DIR__ . '/../js/backoffice.js')) ?>" defer></script></head><body class="<?= $guest ? 'guest' : 'workspace' ?>"><a class="skip-link" href="#main">Aller au contenu</a><?php
    if (!$guest): ?>
    <aside class="sidebar"><a class="brand" href="/admin/admin.php" aria-label="APSI, accueil administration"><img class="brand-logo" src="/img/logo-header-white.webp" width="400" height="226" alt="APSI BTP"><span class="brand-caption">ESPACE DE GESTION</span></a><div class="sidebar-label">VOTRE SITE</div><nav aria-label="Navigation principale"><a class="nav-item <?= $active === 'references' ? 'is-active' : '' ?>" href="/admin/admin.php"><?= icon('grid') ?>Références</a><a class="nav-item <?= $active === 'add' ? 'is-active' : '' ?>" href="/admin/add.php"><?= icon('plus') ?>Nouvelle référence</a><a class="nav-item" href="/references" target="_blank" rel="noopener"><?= icon('arrow') ?>Voir le site<span class="external-hint">↗</span></a></nav><div class="sidebar-bottom"><div class="workspace-account"><span class="avatar">A</span><span><strong>Équipe APSI</strong><small>Administration du site</small></span><span class="online-dot" title="Connecté"></span></div><a class="nav-item <?= $active === 'settings' ? 'is-active' : '' ?>" href="/admin/change_password.php"><?= icon('lock') ?>Sécurité du compte</a><form method="post" action="/admin/logout.php"><?= csrfField() ?><button class="nav-item" type="submit"><?= icon('logout') ?>Se déconnecter</button></form></div></aside>
    <div class="workspace-body"><header class="topbar"><span>APSI BTP <span class="slash">/</span> <strong><?= h($title) ?></strong></span><span class="private-label"><?= icon('lock') ?>Espace privé</span></header>
    <?php endif; ?><main id="main" class="<?= $guest ? 'auth-main' : 'main-content' ?>"><?php
    if (!$guest && ($message = notice())): ?><div class="notice success" role="status"><?= icon('check') ?><span><?= h($message) ?></span></div><?php endif;
}
function pageEnd(bool $guest = false): void { ?></main><?php if (!$guest): ?></div><?php endif; ?></body></html><?php }
function errorsBlock(array $errors): void {
    if (!$errors) return;
    ?><div class="notice error" role="alert" tabindex="-1" id="form-errors"><div><strong>Vérifiez les informations suivantes</strong><ul><?php foreach ($errors as $key => $error): ?><li><a href="#<?= h($key) ?>"><?= h($error) ?></a></li><?php endforeach; ?></ul></div></div><?php
}
