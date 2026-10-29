<?php
/** Common page identity, social locale and navigational structured data. */
$seoPages = [
    'home' => ['/', 'Accueil'],
    'about' => ['/aboutUs', 'Qui sommes-nous'],
    'professions' => ['/professions', 'Nos métiers'],
    'references' => ['/references', 'Nos références'],
    'clients' => ['/clients', 'Nos clients'],
    'contact' => ['/contact', 'Nous contacter'],
    'legal' => ['/legalNotices', 'Mentions légales'],
    'privacy' => ['/privacyPolicy', 'Politique de confidentialité'],
];
$seoPage = $seoPages[$activePage ?? 'home'] ?? null;
$seoProject = isset($ref) && is_array($ref) && isset($ref['titre']);
if ($seoProject) $seoPage = ['/reference/' . (int)$id, $ref['titre']];
$seoGraph = [];
if ($seoPage) {
    $seoUrl = 'https://apsi-btp.fr' . $seoPage[0];
    $seoGraph[] = [
        '@type' => 'WebPage', '@id' => $seoUrl . '#webpage', 'url' => $seoUrl,
        'name' => $seoPage[1] . ' | APSI BTP', 'inLanguage' => 'fr-FR',
        'isPartOf' => ['@id' => 'https://apsi-btp.fr/#website'],
    ];
    if ($seoPage[0] !== '/') {
        $crumbs = [['@type' => 'ListItem', 'position' => 1, 'name' => 'Accueil', 'item' => 'https://apsi-btp.fr/']];
        if ($seoProject) $crumbs[] = ['@type' => 'ListItem', 'position' => 2, 'name' => 'Nos références', 'item' => 'https://apsi-btp.fr/references'];
        $crumbs[] = ['@type' => 'ListItem', 'position' => count($crumbs) + 1, 'name' => $seoPage[1], 'item' => $seoUrl];
        $seoGraph[] = ['@type' => 'BreadcrumbList', '@id' => $seoUrl . '#breadcrumb', 'itemListElement' => $crumbs];
    } else {
        $seoGraph[] = ['@type' => 'WebSite', '@id' => 'https://apsi-btp.fr/#website', 'url' => 'https://apsi-btp.fr/', 'name' => 'APSI BTP', 'inLanguage' => 'fr-FR'];
    }
}
?>
    <link rel="preload" href="/font/web/apsi-variable-1.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="/font/web/apsi-variable-3.woff2" as="font" type="font/woff2" crossorigin>
    <meta property="og:locale" content="fr_FR">
    <meta property="og:site_name" content="APSI BTP">
<?php if ($seoGraph): ?>
    <script type="application/ld+json"><?= json_encode(['@context' => 'https://schema.org', '@graph' => $seoGraph], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?></script>
<?php endif; ?>
