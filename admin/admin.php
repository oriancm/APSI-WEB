<?php
require_once __DIR__ . '/bootstrap.php';
requireAdmin();
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/reference_service.php';
$db = database(); $errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    try {
        if (textValue($_POST, 'confirmation') !== 'SUPPRIMER' || !ctype_digit(textValue($_POST, 'delete_id'))) throw new ReferenceValidation(['general' => 'Confirmez la suppression en saisissant SUPPRIMER.']);
        deleteReference($db, (int)$_POST['delete_id'], textValue($_POST, 'version'));
        auditEvent('reference.deleted', (int)$_POST['delete_id']);
        flash('La référence a été supprimée du site.');
        redirect('/admin/admin.php');
    } catch (ReferenceValidation $error) { $errors = $error->errors; }
    catch (Throwable $error) { logAdminError($error); $errors = ['general' => 'La suppression a échoué. Aucune donnée n’a été supprimée.']; }
}
$references = $db->query('SELECT * FROM reference ORDER BY id DESC')->fetchAll();
$photos = $db->query('SELECT * FROM photo ORDER BY orderPic, id')->fetchAll();
$photoMap = []; foreach ($photos as $photo) $photoMap[$photo['idR']][] = $photo;
$ongoing = count(array_filter($references, fn($r) => (int)$r['statut'] === 2));
$delivered = count(array_filter($references, fn($r) => (int)$r['statut'] === 1));
pageStart('Références');
?>
<div class="page-heading"><div><div class="eyebrow">VOTRE PORTFOLIO</div><h1>Vos références<span class="heading-dot">.</span></h1><p>Les projets qui racontent le savoir-faire d’APSI.</p></div><a class="button primary" href="/admin/add.php"><?= icon('plus') ?>Créer une référence</a></div>
<?php errorsBlock($errors); ?>
<div class="stats-grid"><div class="stat"><span>Références en ligne</span><strong><?= count($references) ?><small>projets publiés</small></strong><span class="stat-icon"><?= icon('grid') ?></span></div><div class="stat"><span>Travaux en cours</span><strong><?= $ongoing ?><small>projets en réalisation</small></strong><span class="stat-icon"><?= icon('edit') ?></span></div><div class="stat"><span>Opérations livrées</span><strong><?= $delivered ?><small>projets terminés</small></strong><span class="stat-icon"><?= icon('check') ?></span></div></div>
<section class="portfolio-section" aria-label="Liste des références"><div class="section-heading"><h2>Tous vos projets <span class="count-badge"><?= count($references) ?></span></h2><span class="muted">Les modifications sont publiées après enregistrement.</span></div>
<div class="filters"><label class="search-field"><?= icon('search') ?><span class="sr-only">Rechercher une référence</span><input id="reference-search" type="search" placeholder="Rechercher un projet, une ville…" autocomplete="off"></label><div class="filter-select"><label class="sr-only" for="domain-filter">Filtrer par domaine</label><select id="domain-filter"><option value="">Tous les domaines</option><?php foreach (REFERENCE_DOMAINS as $value => $label): ?><option value="<?= $value ?>"><?= h($label) ?></option><?php endforeach; ?></select></div><div class="filter-select"><label class="sr-only" for="status-filter">Filtrer par statut</label><select id="status-filter"><option value="">Tous les statuts</option><?php foreach (REFERENCE_STATUSES as $value => $label): ?><option value="<?= $value ?>"><?= h($label) ?></option><?php endforeach; ?></select></div></div><p id="filter-count" class="sr-only" aria-live="polite"></p>
<div class="project-grid"><?php foreach ($references as $ref): $pics = $photoMap[$ref['id']] ?? []; $cover = isset($pics[0]) ? imagePath($pics[0]['titre'], true) : null; ?>
<article class="project-card" data-search="<?= h($ref['titre'] . ' ' . $ref['commune']) ?>" data-domain="<?= h($ref['domaine']) ?>" data-status="<?= h($ref['statut']) ?>"><a class="project-cover" href="/admin/mod.php?id=<?= (int)$ref['id'] ?>" aria-label="Modifier <?= h($ref['titre']) ?>"><?php if ($cover): ?><img src="/pic/<?= h($cover) ?>" alt="" loading="lazy"><?php else: ?><span class="cover-placeholder"><?= icon('photo') ?></span><?php endif; ?><span class="photo-count"><?= icon('photo') ?><?= count($pics) ?></span></a><div class="project-content"><div class="project-meta"><span class="status-badge status-<?= (int)$ref['statut'] ?>"><span></span><?= h(REFERENCE_STATUSES[(int)$ref['statut']] ?? 'Statut à renseigner') ?></span><span class="project-id">N° <?= str_pad((string)$ref['id'], 2, '0', STR_PAD_LEFT) ?></span></div><h3><a href="/admin/mod.php?id=<?= (int)$ref['id'] ?>"><?= h($ref['titre']) ?></a></h3><p class="project-location"><?= h($ref['commune'] ?: 'Ville à renseigner') ?></p><p class="project-domain"><?= h(REFERENCE_DOMAINS[(int)$ref['domaine']] ?? 'Domaine à renseigner') ?></p><div class="project-actions"><a class="edit-link" href="/admin/mod.php?id=<?= (int)$ref['id'] ?>"><?= icon('edit') ?>Modifier</a><a class="icon-button" href="/reference/<?= (int)$ref['id'] ?>" target="_blank" rel="noopener" aria-label="Voir <?= h($ref['titre']) ?> sur le site"><?= icon('arrow') ?></a><button type="button" class="icon-button danger" data-delete-id="<?= (int)$ref['id'] ?>" data-delete-title="<?= h($ref['titre']) ?>" data-delete-version="<?= h(referenceVersion($ref, $pics)) ?>" aria-label="Supprimer <?= h($ref['titre']) ?>"><?= icon('trash') ?></button></div></div></article>
<?php endforeach; ?></div><div id="empty-results" class="empty-state" <?= $references ? 'hidden' : '' ?>><?= icon('search') ?><h3>Aucun projet trouvé</h3><p>Essayez une autre recherche ou créez votre première référence.</p><a class="button secondary" href="/admin/add.php">Créer une référence</a></div></section>
<dialog id="delete-dialog" class="modal"><form method="post" action="/admin/admin.php"><?= csrfField() ?><input type="hidden" name="delete_id"><input type="hidden" name="version"><div class="eyebrow">SUPPRESSION D’UNE RÉFÉRENCE</div><h2>Retirer ce projet du site ?</h2><p id="delete-project-title"></p><p>La référence et sa galerie ne seront plus visibles. Cette action nécessite une restauration de sauvegarde pour être annulée.</p><label for="confirmation">Saisissez <strong>SUPPRIMER</strong> pour confirmer</label><input id="confirmation" name="confirmation" autocomplete="off" required pattern="SUPPRIMER"><div class="modal-actions"><button type="button" class="button secondary" data-close-dialog>Annuler</button><button type="submit" class="button destructive">Supprimer la référence</button></div></form></dialog>
<?php pageEnd(); ?>
