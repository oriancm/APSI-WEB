<?php
declare(strict_types=1);

const APSI_REFERENCE_SORTS = [
    'manual' => 'Ordre manuel (petits numéros en premier)',
    'date_desc' => 'Année du projet : plus récentes en premier',
    'date_asc' => 'Année du projet : plus anciennes en premier',
    'budget_desc' => 'Montant des travaux : décroissant',
    'budget_asc' => 'Montant des travaux : croissant',
    'title_asc' => 'Nom du projet : A à Z',
    'title_desc' => 'Nom du projet : Z à A',
];

function apsiReferenceOrderPath(): string { return __DIR__ . '/../secrets/reference-order.json'; }
function apsiReferenceOrder(): array {
    $path = apsiReferenceOrderPath();
    $raw = is_file($path) ? file_get_contents($path) : '';
    $settings = json_decode($raw ?: '{}', true);
    return ['mode' => isset(APSI_REFERENCE_SORTS[$settings['mode'] ?? '']) ? $settings['mode'] : 'manual',
        'positions' => is_array($settings['positions'] ?? null) ? $settings['positions'] : [],
        'version' => hash('sha256', $raw ?: '')];
}

function apsiSortReferences(array $references, array $settings): array {
    $mode = $settings['mode']; $positions = $settings['positions'];
    $sortValue = static function (array $ref) use ($mode, $positions) {
        if ($mode === 'manual') return isset($positions[$ref['id']]) ? (int)$positions[$ref['id']] : null;
        if (str_starts_with($mode, 'date_')) return is_numeric($ref['anneeF'] ?? null) ? (int)$ref['anneeF'] : (is_numeric($ref['anneeD'] ?? null) ? (int)$ref['anneeD'] : null);
        if (str_starts_with($mode, 'budget_')) return is_numeric($ref['montant'] ?? null) ? (float)$ref['montant'] : null;
        $title = (string)$ref['titre'];
        $ascii = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $title);
        return strtolower($ascii === false ? $title : $ascii);
    };
    usort($references, static function ($a, $b) use ($sortValue, $mode) {
        $left = $sortValue($a); $right = $sortValue($b);
        // Missing years, amounts and positions always follow populated values.
        if ($left === null || $right === null) $result = ($left === null) <=> ($right === null);
        else $result = str_ends_with($mode, '_desc') ? ($right <=> $left) : ($left <=> $right);
        return $result ?: ((int)$a['id'] <=> (int)$b['id']);
    });
    return $references;
}

function apsiSaveReferenceOrder(array $input, array $ids): void {
    $mode = $input['sort_mode'] ?? null;
    $positions = $input['positions'] ?? null;
    if (!is_string($mode) || !isset(APSI_REFERENCE_SORTS[$mode]) || !is_array($positions)) throw new InvalidArgumentException('Choisissez un mode de classement valide.');
    $clean = [];
    foreach ($positions as $id => $position) {
        if (!in_array((int)$id, $ids, true) || !ctype_digit((string)$id) || !is_string($position)) throw new InvalidArgumentException('La liste des références a changé. Rechargez cette page.');
        $position = trim($position);
        if ($position === '') continue;
        if (!ctype_digit($position) || (int)$position < 1 || (int)$position > 99999) throw new InvalidArgumentException('Chaque position doit être un nombre entre 1 et 99 999.');
        $clean[(string)$id] = (int)$position;
    }
    $path = apsiReferenceOrderPath(); $directory = dirname($path);
    if (!is_dir($directory) && !mkdir($directory, 0700, true) && !is_dir($directory)) throw new RuntimeException('Réglages indisponibles.');
    $lock = fopen($path . '.lock', 'c');
    if (!$lock || !flock($lock, LOCK_EX)) throw new RuntimeException('Réglages indisponibles.');
    $temp = null;
    try {
        chmod($path . '.lock', 0600);
        $current = apsiReferenceOrder();
        if (!is_string($input['order_version'] ?? null) || !hash_equals($current['version'], $input['order_version'])) throw new InvalidArgumentException('Le classement a changé dans un autre onglet. Rechargez la page avant de recommencer.');
        $temp = tempnam($directory, '.order-');
        if ($temp === false) throw new RuntimeException('Écriture des réglages impossible.');
        chmod($temp, 0600);
        $json = json_encode(['mode' => $mode, 'positions' => $clean], JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        if (file_put_contents($temp, $json) !== strlen($json) || !rename($temp, $path)) throw new RuntimeException('Écriture des réglages impossible.');
    } finally {
        if ($temp && is_file($temp)) unlink($temp);
        flock($lock, LOCK_UN); fclose($lock);
    }
}
