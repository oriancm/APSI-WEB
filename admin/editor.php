<?php
require_once __DIR__ . '/bootstrap.php';
requireAdmin();
require_once __DIR__ . '/reference_service.php';
require_once __DIR__ . '/layout.php';
$db = database(); $id = null; $reference = null; $errors = [];
if (!empty($editingReference)) {
    $rawId = $_GET['id'] ?? '';
    if (!is_string($rawId) || !ctype_digit($rawId) || (int)$rawId < 1) { http_response_code(404); exit('Référence introuvable.'); }
    $id = (int)$rawId;
    $q = $db->prepare('SELECT * FROM reference WHERE id = ?'); $q->execute([$id]); $reference = $q->fetch();
    if (!$reference) { http_response_code(404); exit('Référence introuvable.'); }
}
$photos = $id ? referencePhotos($db, $id) : [];
$values = $reference ?: array_fill_keys(REFERENCE_FIELDS, '');
[$values['city'], $values['postal_code']] = locationParts((string)($values['commune'] ?? ''));
if (!$id) $values['statut'] = '2';
$version = $id ? referenceVersion($reference, $photos) : '';
$submission = bin2hex(random_bytes(16));
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $submission = textValue($_POST, 'submission');
    $form = $_SESSION['forms'][$submission] ?? null;
    if (!$form || $form['reference'] !== $id) { http_response_code(403); exit('Ce formulaire a expiré. Rechargez la page.'); }
    if ($form['result']) redirect('/admin/mod.php?id=' . $form['result']);
    try {
        $savedId = saveReference($db, $_POST, $_FILES['images'] ?? [], $id, __DIR__ . '/../pic');
        auditEvent($id ? 'reference.updated' : 'reference.created', $savedId);
        $_SESSION['forms'][$submission]['result'] = $savedId;
        flash($id ? 'La référence et ses photos ont été mises à jour sur le site.' : 'Votre nouvelle référence est maintenant en ligne.');
        redirect(textValue($_POST, 'intent') === 'save_new' ? '/admin/add.php' : '/admin/mod.php?id=' . $savedId);
    } catch (ReferenceValidation $error) { $errors = $error->errors; }
    catch (Throwable $error) { logAdminError($error); $errors = ['general' => 'L’enregistrement a échoué. Aucun changement n’a été publié. Réessayez dans quelques instants.']; }
    foreach (array_merge(REFERENCE_FIELDS, ['city', 'postal_code']) as $field) $values[$field] = textValue($_POST, $field);
    $version = textValue($_POST, 'version');
    if (!empty($_FILES['images']['name'][0])) $errors['photos'] = ($errors['photos'] ?? '') . ' Veuillez sélectionner à nouveau les nouvelles photos avant d’enregistrer.';
} else {
    $_SESSION['forms'] ??= [];
    while (count($_SESSION['forms']) >= 20) array_shift($_SESSION['forms']);
    $_SESSION['forms'][$submission] = ['reference' => $id, 'result' => null];
}
function field(string $name, string $label, array $values, array $errors, string $type = 'text', string $extra = '', string $help = ''): void {
    ?><div class="field"><label for="<?= h($name) ?>"><?= h($label) ?></label><input type="<?= h($type) ?>" id="<?= h($name) ?>" name="<?= h($name) ?>" value="<?= h($values[$name] ?? '') ?>" <?= $extra ?> <?= isset($errors[$name]) ? 'aria-invalid="true"' : '' ?><?php if ($help || isset($errors[$name])): ?> aria-describedby="<?= h($name) ?>-help"<?php endif; ?>><?php if ($help || isset($errors[$name])): ?><p class="field-help <?= isset($errors[$name]) ? 'field-error' : '' ?>" id="<?= h($name) ?>-help"><?= h($errors[$name] ?? $help) ?></p><?php endif; ?></div><?php
}
pageStart($id ? 'Modifier une référence' : 'Nouvelle référence', $id ? 'references' : 'add');
?>
<a class="back-link" href="/admin/admin.php"><?= icon('back') ?>Toutes les références</a>
<div class="page-heading editor-heading"><div><div class="eyebrow"><?= $id ? 'RÉFÉRENCE N° ' . str_pad((string)$id, 2, '0', STR_PAD_LEFT) : 'UN NOUVEAU PROJET À PARTAGER' ?></div><h1><?= $id ? 'Modifier la référence' : 'Créer une référence' ?><span class="heading-dot">.</span></h1><p>Les informations essentielles d’abord. Les détails à votre rythme.</p></div><?php if ($id): ?><a class="button secondary" href="/reference/<?= $id ?>" target="_blank" rel="noopener">Voir sur le site <?= icon('arrow') ?></a><?php endif; ?></div>
<?php errorsBlock($errors); ?>
<form id="reference-form" method="post" enctype="multipart/form-data" data-reference-editor data-existing="<?= $id ? '1' : '0' ?>" data-reference-id="<?= h($id ?? '') ?>" class="editor-layout">
<?= csrfField() ?><input type="hidden" name="submission" value="<?= h($submission) ?>"><input type="hidden" name="version" value="<?= h($version) ?>"><input type="hidden" name="photo_order" id="photo-order" value="">
<div class="editor-sections">
<section class="panel" id="project"><div class="panel-heading"><span class="section-number">01</span><div><h2>Le projet</h2><p>De quoi s’agit-il et où se situe-t-il ?</p></div><span class="required-label">* Obligatoire</span></div>
<?php field('titre', 'Nom de la référence *', $values, $errors, 'text', 'required maxlength="100" placeholder="Ex. Réhabilitation du collège Paul Cézanne"', 'Un titre court et précis, visible sur le site.'); ?>
<div class="field-grid"><div class="field"><label for="domaine">Domaine du projet *</label><select name="domaine" id="domaine" required><option value="">Choisir un domaine</option><?php foreach (REFERENCE_DOMAINS as $value => $label): ?><option value="<?= $value ?>" <?= (string)$values['domaine'] === (string)$value ? 'selected' : '' ?>><?= h($label) ?></option><?php endforeach; ?></select></div><div class="field"><label for="statut">Avancement</label><select name="statut" id="statut"><option value="">Non renseigné</option><?php foreach (REFERENCE_STATUSES as $value => $label): ?><option value="<?= $value ?>" <?= (string)$values['statut'] === (string)$value ? 'selected' : '' ?>><?= h($label) ?></option><?php endforeach; ?></select></div></div>
<div class="location-block"><div class="field-grid location-fields"><?php field('postal_code', 'Code postal', $values, $errors, 'text', 'inputmode="numeric" maxlength="5" pattern="[0-9]{5}" autocomplete="off" placeholder="Ex. 84000" aria-controls="location-results"'); ?><?php field('city', 'Ville *', $values, $errors, 'text', 'required maxlength="85" autocomplete="off" placeholder="Ex. Avignon" aria-controls="location-results"'); ?></div><p class="field-help" id="location-help" aria-live="polite">Saisissez une ville ou un code postal, puis choisissez la bonne commune.</p><div id="location-results" class="location-results" aria-label="Communes proposées" hidden></div></div>
<div class="field"><label for="description">Présentation du projet <span class="optional">Facultatif</span></label><textarea id="description" name="description" maxlength="10000" rows="5" placeholder="Décrivez l’opération, ses enjeux et la mission confiée à APSI…"><?= h($values['description']) ?></textarea><div class="textarea-footer"><span>Quelques phrases suffisent pour présenter votre intervention.</span><span id="description-count"></span></div></div></section>
<section class="panel" id="photos"><div class="panel-heading"><span class="section-number">02</span><div><h2>Les photos du projet</h2><p>La première photo sera la couverture de la référence.</p></div><span class="count-badge" id="photo-total"><?= count($photos) ?></span></div><div class="upload-zone" id="upload-zone"><span class="upload-icon"><?= icon('photo') ?></span><strong>Déposez vos photos ici</strong><span>ou choisissez-les depuis votre ordinateur</span><label class="button secondary upload-button" for="image-files"><?= icon('plus') ?>Ajouter des photos</label><input type="file" name="images[]" id="image-files" accept="image/jpeg,image/png,image/webp" multiple aria-describedby="upload-help"><p id="upload-help">JPG, PNG ou WebP · 15 Mo par photo · 20 photos maximum</p><span class="conversion-note"><?= icon('check') ?>Conversion et optimisation WebP automatiques</span></div><div id="upload-errors" class="notice error" role="alert" hidden></div><p class="gallery-help">Glissez les photos pour les réordonner, ou utilisez les flèches. La couverture peut être changée à tout moment.</p><div class="photo-grid" id="photo-gallery"><?php foreach ($photos as $index => $photo): ?><div class="photo-tile" data-photo-key="existing:<?= (int)$photo['id'] ?>" draggable="true"><div class="photo-tile-image"><img src="/pic/<?= h(imagePath($photo['titre'], true)) ?>" alt="Photo <?= $index + 1 ?> de la référence" loading="lazy"><span class="cover-label" <?= $index !== 0 ? 'hidden' : '' ?>>Couverture</span></div><div class="photo-tile-footer"><span class="photo-position">Photo <?= $index + 1 ?></span><div><button class="mini-button" type="button" data-photo-move="-1" aria-label="Déplacer cette photo vers le début">←</button><button class="mini-button" type="button" data-photo-move="1" aria-label="Déplacer cette photo vers la fin">→</button><button class="mini-button danger" type="button" data-photo-remove aria-label="Retirer cette photo">×</button></div></div></div><?php endforeach; ?></div><div id="deleted-photos"></div><p id="gallery-status" class="field-help" role="status" aria-live="polite"></p><noscript><p class="notice">Sans JavaScript, les nouvelles photos sont ajoutées à la fin de la galerie. Activez JavaScript pour réordonner les photos et rechercher une commune.</p></noscript></section>
<section class="panel" id="details"><div class="panel-heading"><span class="section-number">03</span><div><h2>Les détails de l’opération</h2><p>Complétez uniquement ce que vous souhaitez afficher sur le site.</p></div><span class="optional">Facultatif</span></div><div class="field-grid"><?php field('anneeD', 'Année de début', $values, $errors, 'number', 'min="1901" max="2155" placeholder="Ex. 2025"'); ?><?php field('anneeF', 'Année de fin', $values, $errors, 'number', 'min="1901" max="2155" placeholder="Ex. 2027"'); ?><?php field('duree_travaux_mois', 'Durée des travaux (mois)', $values, $errors, 'number', 'min="0" max="1200" placeholder="Ex. 18"'); ?><?php field('montant', 'Montant des travaux HT (€)', $values, $errors, 'text', 'inputmode="decimal" placeholder="Ex. 2 500 000"'); ?></div><div class="field-divider"></div><?php field('moa', 'Maître d’ouvrage', $values, $errors, 'text', 'maxlength="100" placeholder="Nom du client ou du maître d’ouvrage"'); ?><div class="field-grid"><?php field('archi', 'Architecte', $values, $errors, 'text', 'maxlength="100"'); ?><?php field('eMoe', 'Équipe de maîtrise d’œuvre', $values, $errors, 'text', 'maxlength="100"'); ?><?php field('nbPhase', 'Nombre de phases', $values, $errors, 'number', 'min="0" max="10000"'); ?><?php field('nbE', 'Nombre d’entreprises', $values, $errors, 'number', 'min="0" max="10000"'); ?><?php field('nombre_lots', 'Nombre de lots', $values, $errors, 'number', 'min="0" max="10000"'); ?></div></section>
</div><aside class="editor-aside"><div class="preview-card"><div class="eyebrow">VOTRE RÉFÉRENCE EN UN COUP D’ŒIL</div><div class="preview-image" id="preview-image"><?= icon('photo') ?></div><div class="preview-content"><span class="status-badge" id="preview-status">Travaux en cours</span><h2 id="preview-title"><?= h($values['titre'] ?: 'Le nom de votre projet') ?></h2><p id="preview-location"><?= h($values['commune'] ?: 'La ville du projet') ?></p><span class="preview-domain" id="preview-domain"></span></div><div class="preview-disclaimer">Aperçu indicatif de votre référence.</div></div><div class="editor-guide"><strong>Une référence claire, en quelques minutes.</strong><ol><li>Nommez et localisez le projet.</li><li>Choisissez une belle photo de couverture.</li><li>Ajoutez vos détails, puis publiez.</li></ol><p>Seuls le nom, la ville, le domaine et une photo sont obligatoires.</p></div></aside>
<div class="save-bar"><div><span class="save-indicator" id="save-indicator"></span><span id="save-status"><?= $id ? 'Référence en ligne' : 'Nouvelle référence' ?></span><small>Enregistrement manuel · Ctrl / ⌘ + Entrée</small></div><div class="save-actions"><button class="button secondary" type="submit" name="intent" value="save_new">Enregistrer et en créer une autre</button><button class="button primary" type="submit" name="intent" value="save"><?= icon('check') ?><?= $id ? 'Enregistrer les modifications' : 'Enregistrer et publier' ?></button></div></div>
</form>
<?php pageEnd(); ?>
