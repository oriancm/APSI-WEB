<?php
declare(strict_types=1);
require_once __DIR__ . '/image_optimizer.php';

const REFERENCE_DOMAINS = [1 => 'Résidences universitaires', 2 => 'Équipements sportifs', 3 => 'Équipements culturels', 4 => 'Groupes scolaires et collèges', 5 => 'Aménagements urbains', 6 => 'Bâtiments publics', 7 => 'Crèches', 8 => 'Centres d’incendie et de secours', 9 => 'Santé', 10 => 'Logements sociaux', 11 => 'Monuments historiques', 12 => 'Restructuration et réhabilitation'];
const REFERENCE_STATUSES = [1 => 'Opération livrée', 2 => 'Travaux en cours', 3 => 'Conception en cours'];
const REFERENCE_FIELDS = ['titre', 'commune', 'domaine', 'description', 'anneeD', 'anneeF', 'statut', 'moa', 'archi', 'eMoe', 'nbPhase', 'montant', 'nbE', 'duree_travaux_mois', 'nombre_lots'];

class ReferenceValidation extends RuntimeException {
    public array $errors;
    public function __construct(array $errors) { parent::__construct('Vérifiez le formulaire.'); $this->errors = $errors; }
}
function textValue(array $input, string $key): string {
    return is_string($input[$key] ?? null) ? trim($input[$key]) : '';
}
function referenceValues(array $input, ?array $original = null): array {
    $errors = []; $values = [];
    foreach (['titre' => 100, 'description' => 10000, 'moa' => 100, 'archi' => 100, 'eMoe' => 100] as $field => $max) {
        $value = textValue($input, $field);
        if (mb_strlen($value) > $max) $errors[$field] = 'Ce champ dépasse la longueur autorisée (' . $max . ' caractères).';
        $values[$field] = $value === '' ? null : $value;
    }
    if (!$values['titre']) $errors['titre'] = 'Donnez un nom à cette référence.';
    $city = textValue($input, 'city'); $postal = textValue($input, 'postal_code');
    if (mb_strlen($city) < 2 || mb_strlen($city) > 85) $errors['city'] = 'Renseignez une ville (2 à 85 caractères).';
    if ($postal !== '' && !preg_match('/^\d{5}$/D', $postal)) $errors['postal_code'] = 'Le code postal doit comporter 5 chiffres.';
    $oldCity = $original ? locationParts((string)$original['commune']) : ['', ''];
    // Preserve legacy department-formatted locations until the manager actually changes them.
    $values['commune'] = $original && $city === $oldCity[0] && $postal === $oldCity[1]
        ? $original['commune'] : $city . ($postal !== '' ? ' (' . $postal . ')' : '');
    foreach (['domaine' => array_keys(REFERENCE_DOMAINS), 'statut' => array_keys(REFERENCE_STATUSES)] as $field => $allowed) {
        $raw = textValue($input, $field);
        if ($raw !== '' && (!ctype_digit($raw) || !in_array((int)$raw, $allowed, true))) $errors[$field] = 'Choisissez une valeur de la liste.';
        $values[$field] = $raw === '' ? null : (int)$raw;
    }
    if ($values['domaine'] === null) $errors['domaine'] = 'Choisissez le domaine du projet.';
    foreach (['anneeD' => 2155, 'anneeF' => 2155, 'nbPhase' => 10000, 'nbE' => 10000, 'nombre_lots' => 10000, 'duree_travaux_mois' => 1200] as $field => $max) {
        $raw = textValue($input, $field);
        if ($raw !== '' && (!ctype_digit($raw) || (int)$raw > $max || (str_starts_with($field, 'annee') && (int)$raw < 1901))) $errors[$field] = 'Renseignez un nombre valide pour ce champ.';
        $values[$field] = $raw === '' ? null : (int)$raw;
    }
    if ($values['anneeD'] && $values['anneeF'] && $values['anneeF'] < $values['anneeD']) $errors['anneeF'] = 'La fin du projet ne peut pas précéder son début.';
    $amount = preg_replace('/[\s\x{00a0}\x{202f}€]/u', '', textValue($input, 'montant'));
    $amount = str_replace(',', '.', $amount);
    if ($amount !== '' && (!preg_match('/^\d{1,8}(?:\.\d{1,2})?$/D', $amount) || (float)$amount > 99999999.99)) $errors['montant'] = 'Renseignez un montant entre 0 et 99 999 999,99 €.';
    $values['montant'] = $amount === '' ? null : $amount;
    if ($errors) throw new ReferenceValidation($errors);
    return $values;
}
function locationParts(string $commune): array {
    if (preg_match('/^(.*?)\s*\((\d{5})\)$/u', $commune, $parts)) return [trim($parts[1]), $parts[2]];
    return [trim(preg_replace('/\s*\([\dABab]{2,3}\)$/u', '', $commune)), ''];
}
function referencePhotos(PDO $db, int $id): array {
    $q = $db->prepare('SELECT * FROM photo WHERE idR = ? ORDER BY orderPic, id'); $q->execute([$id]); return $q->fetchAll(PDO::FETCH_ASSOC);
}
function referenceVersion(array $ref, array $photos): string { return hash('sha256', serialize([$ref, $photos])); }
function lockSuffix(PDO $db): string { return $db->getAttribute(PDO::ATTR_DRIVER_NAME) === 'mysql' ? ' FOR UPDATE' : ''; }
function imagePath(string $name, bool $thumbnail = false): string {
    $stem = pathinfo(basename($name), PATHINFO_FILENAME);
    if ($thumbnail && is_file(__DIR__ . '/../pic/cards/' . $stem . '.webp')) return 'cards/' . $stem . '.webp';
    return is_file(__DIR__ . '/../pic/' . $stem . '.webp') ? $stem . '.webp' : basename($name);
}
function removeImageFiles(string $name, string $directory): void {
    $name = basename($name); $stem = pathinfo($name, PATHINFO_FILENAME);
    foreach ([$name, $stem . '.webp', 'mobile/' . $stem . '.webp', 'cards/' . $stem . '.webp', 'previews/' . $stem . '.webp'] as $relative) {
        $path = $directory . '/' . $relative;
        if (is_file($path)) @unlink($path);
    }
}
function uploadBatch(array $files, string $directory): array {
    $names = $files['name'] ?? [];
    if (!is_array($names) || count($names) > 20) throw new ReferenceValidation(['photos' => 'Ajoutez au maximum 20 photos à la fois.']);
    $created = []; $total = 0;
    try {
        foreach ($names as $index => $name) {
            $error = $files['error'][$index] ?? UPLOAD_ERR_NO_FILE;
            if ($error === UPLOAD_ERR_NO_FILE) continue;
            if ($error !== UPLOAD_ERR_OK) throw new ReferenceValidation(['photos' => 'Une photo n’a pas pu être reçue. Vérifiez la taille des fichiers puis réessayez.']);
            $tmp = $files['tmp_name'][$index] ?? '';
            $size = is_string($tmp) && is_file($tmp) ? filesize($tmp) : 0;
            $total += $size;
            $ext = strtolower(pathinfo(is_string($name) ? $name : '', PATHINFO_EXTENSION));
            if (!$size || $size > 15 * 1024 * 1024 || $total > 60 * 1024 * 1024) throw new ReferenceValidation(['photos' => 'Limite : 15 Mo par photo et 60 Mo par enregistrement.']);
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true) || !is_uploaded_file($tmp)) throw new ReferenceValidation(['photos' => 'Utilisez des photos JPG, PNG ou WebP.']);
            $info = @getimagesize($tmp);
            if (!$info || !in_array($info['mime'], ['image/jpeg', 'image/png', 'image/webp'], true) || $info[0] * $info[1] > 20000000) throw new ReferenceValidation(['photos' => 'Photo illisible ou trop grande (maximum 20 millions de pixels).']);
            $filename = 'apsi-' . bin2hex(random_bytes(16)) . '.webp';
            if (!is_dir($directory) && !mkdir($directory, 0755, true)) throw new RuntimeException('Dossier images indisponible.');
            $staged = tempnam(sys_get_temp_dir(), 'apsi-upload-');
            if (!$staged) throw new RuntimeException('Dossier temporaire indisponible.');
            chmod($staged, 0600);
            try {
                if (!move_uploaded_file($tmp, $staged)) throw new RuntimeException('Écriture image impossible.');
                if (!optimizeUploadedImage($staged, $directory . '/' . $filename)) throw new ReferenceValidation(['photos' => 'La conversion d’une photo a échoué. Aucun changement n’a été enregistré.']);
            } finally { if (is_file($staged)) @unlink($staged); }
            $created[(string)$index] = $filename;
        }
    } catch (Throwable $error) {
        foreach ($created as $name) removeImageFiles($name, $directory);
        throw $error;
    }
    return $created;
}
function saveReference(PDO $db, array $input, array $uploads, ?int $id, string $directory): int {
    $q = $db->prepare('SELECT * FROM reference WHERE id = ?'); $q->execute([$id]); $original = $id ? $q->fetch(PDO::FETCH_ASSOC) : null;
    if ($id && !$original) throw new ReferenceValidation(['general' => 'Cette référence n’existe plus.']);
    $values = referenceValues($input, $original ?: null);
    $created = uploadBatch($uploads, $directory);
    try {
        $db->beginTransaction();
        $existing = [];
        if ($id) {
            $q = $db->prepare('SELECT * FROM reference WHERE id = ?' . lockSuffix($db)); $q->execute([$id]); $current = $q->fetch(PDO::FETCH_ASSOC);
            $existing = referencePhotos($db, $id);
            if (!$current || !hash_equals(referenceVersion($current, $existing), textValue($input, 'version'))) throw new ReferenceValidation(['general' => 'Cette référence a été modifiée dans un autre onglet. Copiez votre texte puis rechargez la page avant de recommencer.']);
        }
        $deleted = $input['delete_photos'] ?? [];
        if (!is_array($deleted) || array_filter($deleted, fn($v) => !is_string($v) || !ctype_digit($v))) throw new ReferenceValidation(['photos' => 'La sélection des photos est invalide.']);
        $existingIds = array_map(fn($p) => (string)$p['id'], $existing);
        if (array_diff($deleted, $existingIds)) throw new ReferenceValidation(['photos' => 'Une photo ne fait pas partie de cette référence.']);
        $kept = array_values(array_filter($existing, fn($p) => !in_array((string)$p['id'], $deleted, true)));
        $keys = array_merge(array_map(fn($p) => 'existing:' . $p['id'], $kept), array_map(fn($k) => 'new:' . $k, array_keys($created)));
        if (count($keys) < 1 || count($keys) > 20) throw new ReferenceValidation(['photos' => 'Conservez entre 1 et 20 photos pour cette référence.']);
        $order = textValue($input, 'photo_order');
        $order = $order !== '' ? json_decode($order, true) : $keys;
        if (!is_array($order) || !array_is_list($order) || count($order) !== count($keys) || array_filter($order, fn($v) => !is_string($v)) || array_diff($keys, $order) || array_diff($order, $keys) || count(array_unique($order)) !== count($order)) throw new ReferenceValidation(['photos' => 'L’ordre des photos est invalide. Réessayez après avoir vérifié la galerie.']);
        $params = array_map(fn($field) => $values[$field], REFERENCE_FIELDS);
        if ($id) {
            $q = $db->prepare('UPDATE reference SET ' . implode(', ', array_map(fn($f) => $f . ' = ?', REFERENCE_FIELDS)) . ' WHERE id = ?'); $q->execute([...$params, $id]);
        } else {
            $q = $db->prepare('INSERT INTO reference (' . implode(',', REFERENCE_FIELDS) . ') VALUES (' . implode(',', array_fill(0, count(REFERENCE_FIELDS), '?')) . ')');
            $q->execute($params); $id = (int)$db->lastInsertId();
        }
        foreach ($deleted as $photoId) { $q = $db->prepare('DELETE FROM photo WHERE id = ? AND idR = ?'); $q->execute([$photoId, $id]); }
        foreach ($order as $position => $key) {
            [$type, $value] = explode(':', $key, 2);
            if ($type === 'existing') {
                $q = $db->prepare('UPDATE photo SET orderPic = ? WHERE id = ? AND idR = ?'); $q->execute([$position + 1, $value, $id]);
            } else {
                $filename = $created[$value];
                $q = $db->prepare('INSERT INTO photo (titre,dir,idR,orderPic) VALUES (?,?,?,?)'); $q->execute([$filename, 'pic/' . $filename, $id, $position + 1]);
            }
        }
        $db->commit();
    } catch (Throwable $error) {
        if ($db->inTransaction()) $db->rollBack();
        foreach ($created as $name) removeImageFiles($name, $directory);
        throw $error;
    }
    // Only remove files after commit, and only if no other reference uses that filename.
    try {
        $usedStems = array_map(fn($name) => pathinfo(basename($name), PATHINFO_FILENAME), $db->query('SELECT titre FROM photo')->fetchAll(PDO::FETCH_COLUMN));
        foreach ($existing as $photo) {
            if (!in_array((string)$photo['id'], $deleted, true)) continue;
            if (!in_array(pathinfo(basename($photo['titre']), PATHINFO_FILENAME), $usedStems, true)) removeImageFiles($photo['titre'], $directory);
        }
    } catch (Throwable $error) {
        // A cleanup problem must never report an already committed save as a failure.
        error_log('APSI admin: deferred image cleanup required.');
    }
    return $id;
}
function deleteReference(PDO $db, int $id, string $version): void {
    $db->beginTransaction();
    try {
        $q = $db->prepare('SELECT * FROM reference WHERE id = ?' . lockSuffix($db)); $q->execute([$id]); $ref = $q->fetch(PDO::FETCH_ASSOC);
        if (!$ref || !hash_equals(referenceVersion($ref, referencePhotos($db, $id)), $version)) throw new ReferenceValidation(['general' => 'Cette référence a changé. Rechargez la liste avant de la supprimer.']);
        $q = $db->prepare('DELETE FROM photo WHERE idR = ?'); $q->execute([$id]);
        $q = $db->prepare('DELETE FROM reference WHERE id = ?'); $q->execute([$id]);
        $db->commit();
        // Keep originals on disk after deleting a project, allowing recovery from a private DB backup.
    } catch (Throwable $error) { if ($db->inTransaction()) $db->rollBack(); throw $error; }
}
