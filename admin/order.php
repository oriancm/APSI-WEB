<?php
require_once __DIR__ . '/bootstrap.php';
requireAdmin();
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../functions/reference_order.php';
$db = database(); $errors = [];
$references = $db->query('SELECT * FROM reference ORDER BY id ASC')->fetchAll();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    try {
        apsiSaveReferenceOrder($_POST, array_map(fn($ref) => (int)$ref['id'], $references));
        auditEvent('references.order.updated');
        flash('Le classement des références a été publié sur le site.');
        redirect('/admin/order.php');
    } catch (InvalidArgumentException $error) { $errors['sort_mode'] = $error->getMessage(); }
    catch (Throwable $error) { logAdminError($error); $errors['sort_mode'] = 'Le classement n’a pas pu être enregistré. Réessayez.'; }
}
$settings = apsiReferenceOrder();
$references = apsiSortReferences($references, $settings);
pageStart('Classement des références');
?>
<a class="back-link" href="/admin/admin.php"><?= icon('back') ?>Toutes les références</a>
<div class="page-heading"><div><div class="eyebrow">ORDRE D’AFFICHAGE DU SITE</div><h1>Classer vos références<span class="heading-dot">.</span></h1><p>Choisissez l’ordre des projets dans la liste publique, le carrousel et les suggestions.</p></div></div>
<?php errorsBlock($errors); ?>
<form method="post" action="/admin/order.php" class="panel reference-order-form">
    <?= csrfField() ?><input type="hidden" name="order_version" value="<?= h($settings['version']) ?>">
    <div class="field"><label for="sort_mode">Classer les références par</label><select id="sort_mode" name="sort_mode"><?php foreach (APSI_REFERENCE_SORTS as $value => $label): ?><option value="<?= h($value) ?>" <?= $settings['mode'] === $value ? 'selected' : '' ?>><?= h($label) ?></option><?php endforeach; ?></select><p class="field-help">Le tri par année utilise l’année de fin, ou celle de début si la fin n’est pas renseignée. Les valeurs manquantes sont placées à la fin.</p></div>
    <details class="reference-order-manual" <?= $settings['mode'] === 'manual' ? 'open' : '' ?>><summary>Attribuer les positions manuelles</summary><p class="field-help">Le numéro 1 apparaît en premier. Les références sans numéro viennent ensuite. En cas d’égalité, la référence créée en premier est affichée d’abord. Vos numéros sont conservés quand vous choisissez un autre tri.</p>
    <div class="reference-order-list"><?php foreach ($references as $ref): ?><div class="reference-order-row"><label for="position-<?= (int)$ref['id'] ?>"><strong><?= h($ref['titre']) ?></strong><span><?= h($ref['commune']) ?></span></label><input type="number" min="1" max="99999" step="1" id="position-<?= (int)$ref['id'] ?>" name="positions[<?= (int)$ref['id'] ?>]" value="<?= h($settings['positions'][$ref['id']] ?? '') ?>" placeholder="—" aria-label="Position de <?= h($ref['titre']) ?>"></div><?php endforeach; ?></div></details>
    <button class="button primary" type="submit"><?= icon('check') ?>Enregistrer et publier le classement</button>
</form>
<?php pageEnd(); ?>
