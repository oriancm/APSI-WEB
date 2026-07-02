<?php
$activePage = $activePage ?? '';
$navItems = [
    'about' => ['href' => '/aboutUs', 'label' => 'Qui sommes-nous'],
    'professions' => ['href' => '/professions', 'label' => 'Nos métiers'],
    'references' => ['href' => '/references', 'label' => 'Nos références'],
    'clients' => ['href' => '/clients', 'label' => 'Nos clients'],
];
?>
<header class="site-header" id="site-header">
    <a class="site-header__logo" href="/" aria-label="APSI BTP - Accueil">
        <img src="/img/logo-nav.png" alt="APSI BTP">
    </a>

    <button class="site-header__toggle" type="button" aria-expanded="false" aria-controls="site-nav">
        <i data-lucide="menu" aria-hidden="true"></i>
        <span class="sr-only">Menu</span>
    </button>

    <nav class="site-header__nav" id="site-nav" aria-label="Navigation principale">
        <?php foreach ($navItems as $key => $item): ?>
            <a class="<?= $activePage === $key ? 'active' : '' ?>" href="<?= htmlspecialchars($item['href']) ?>">
                <?= htmlspecialchars($item['label']) ?>
            </a>
        <?php endforeach; ?>
        <a class="site-header__nav-cta <?= $activePage === 'contact' ? 'active' : '' ?>" href="/contact">Nous contacter</a>
    </nav>

</header>
