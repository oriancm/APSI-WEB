<?php
$activePage = $activePage ?? '';
$navItems = [
    'about' => ['href' => '/aboutUs', 'label' => 'Qui sommes-nous'],
    'professions' => ['href' => '/professions', 'label' => 'Nos métiers'],
    'references' => ['href' => '/references', 'label' => 'Nos références'],
    'clients' => ['href' => '/clients', 'label' => 'Nos clients'],
];
?>
<div class="site-header-slot">
<header class="site-header site-header--editorial" id="site-header">
        <div class="site-header__scene" aria-hidden="true"></div>
    <a class="site-header__logo" href="/" aria-label="APSI BTP - Accueil">
        <picture>
            <img src="/img/logo-nav-transparent.png" width="1663" height="946" alt="APSI BTP">
        </picture>
            <img class="site-header__logo-white" src="/img/logo-nav-white.png" width="1146" height="652" alt="" aria-hidden="true">
    </a>

    <button class="site-header__toggle" type="button" aria-expanded="false" aria-controls="site-nav">
        <i data-lucide="menu" aria-hidden="true"></i>
        <span class="sr-only">Menu</span>
    </button>

    <nav class="site-header__nav" id="site-nav" aria-label="Navigation principale">
        <div class="site-header__links">
        <?php foreach ($navItems as $key => $item): ?>
            <a class="<?= $activePage === $key ? 'active' : '' ?>" <?= $activePage === $key ? 'aria-current="page"' : '' ?> href="<?= htmlspecialchars($item['href']) ?>">
                <?= htmlspecialchars($item['label']) ?>
            </a>
        <?php endforeach; ?>
        </div>
        <a class="site-header__nav-cta <?= $activePage === 'contact' ? 'active' : '' ?>" <?= $activePage === 'contact' ? 'aria-current="page"' : '' ?> href="/contact">Nous contacter <i data-lucide="arrow-right" aria-hidden="true"></i></a>
    </nav>

</header>
</div>
