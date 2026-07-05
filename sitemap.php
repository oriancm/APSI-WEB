<?php
header("Content-Type: application/xml; charset=utf-8");

require('admin/db.php');

// Define static pages
$staticPages = [
    '' => ['priority' => '1.0', 'changefreq' => 'monthly'],
    'aboutUs' => ['priority' => '0.8', 'changefreq' => 'monthly'],
    'professions' => ['priority' => '0.8', 'changefreq' => 'monthly'],
    'references' => ['priority' => '0.9', 'changefreq' => 'weekly'],
    'clients' => ['priority' => '0.7', 'changefreq' => 'monthly'],
    'contact' => ['priority' => '0.9', 'changefreq' => 'monthly'],
    'legalNotices' => ['priority' => '0.3', 'changefreq' => 'yearly'],
    'privacyPolicy' => ['priority' => '0.3', 'changefreq' => 'yearly']
];

// Fetch dynamic references from DB
function getReferencesForSitemap($db) {
    try {
        $sql = "SELECT id FROM reference ORDER BY id ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $refs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($refs) && $db instanceof NullDb) {
            return [
                ['id' => 1],
                ['id' => 2],
                ['id' => 3],
                ['id' => 4],
                ['id' => 5],
                ['id' => 6],
                ['id' => 7]
            ];
        }
        return $refs;
    } catch (Throwable $e) {
        return [
            ['id' => 1],
            ['id' => 2],
            ['id' => 3],
            ['id' => 4],
            ['id' => 5],
            ['id' => 6],
            ['id' => 7]
        ];
    }
}

$dynamicRefs = getReferencesForSitemap($db);
$currentDate = date('Y-m-d');

echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($staticPages as $page => $info): ?>
    <url>
        <loc>https://apsi-btp.fr/<?= htmlspecialchars($page) ?></loc>
        <lastmod><?= $currentDate ?></lastmod>
        <changefreq><?= htmlspecialchars($info['changefreq']) ?></changefreq>
        <priority><?= htmlspecialchars($info['priority']) ?></priority>
    </url>
<?php endforeach; ?>
<?php foreach ($dynamicRefs as $ref): ?>
    <url>
        <loc>https://apsi-btp.fr/reference/<?= htmlspecialchars($ref['id']) ?></loc>
        <lastmod><?= $currentDate ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
<?php endforeach; ?>
</urlset>
