<?php
// Shared public project card. Callers supply the reference, cover and domain label.
$cardTag = ($cardHeading ?? 'h2') === 'h3' ? 'h3' : 'h2';
?>
<article class="reference-tile" data-domain="<?= htmlspecialchars($cardRef['domaine'] ?? '0') ?>">
    <a href="/reference/<?= (int)$cardRef['id'] ?>" aria-label="<?= htmlspecialchars($cardRef['titre']) ?>"></a>
    <?php if ($cardDomain !== ''): ?><span class="reference-tile__domain"><?= htmlspecialchars($cardDomain) ?></span><?php endif; ?>
    <?php if ($cardImage): ?>
        <img <?= apsiImageSize(apsiCardImage($cardImage)) ?> src="/pic/<?= htmlspecialchars(apsiCardImage($cardImage)) ?>" alt="<?= htmlspecialchars($cardRef['titre']) ?> à <?= htmlspecialchars($cardRef['commune'] ?? '') ?>" loading="lazy" decoding="async">
    <?php else: ?><div class="reference-tile-empty">Aucune image disponible</div><?php endif; ?>
    <span class="reference-tile__arrow" aria-hidden="true"><i data-lucide="arrow-right"></i></span>
    <div class="reference-tile__copy">
        <<?= $cardTag ?>><?= htmlspecialchars($cardRef['titre']) ?></<?= $cardTag ?>>
        <p><i data-lucide="map-pin" aria-hidden="true"></i><?= htmlspecialchars($cardRef['commune'] ?? '') ?></p>
    </div>
</article>
