<?php
require_once __DIR__ . '/bootstrap.php';
requireAdmin(true);
header('Content-Type: application/json; charset=utf-8');
// Read-only authentication preflight. Refresh form tokens after reauthentication,
// without losing the manager's text, files or original concurrency version.
$raw = $_GET['reference'] ?? '';
if (!is_string($raw) || ($raw !== '' && (!ctype_digit($raw) || (int)$raw < 1))) {
    http_response_code(400); exit('{"authenticated":false}');
}
$reference = $raw === '' ? null : (int)$raw;
$submission = $_GET['submission'] ?? '';
$form = is_string($submission) ? ($_SESSION['forms'][$submission] ?? null) : null;
if (!$form || $form['reference'] !== $reference) {
    $submission = bin2hex(random_bytes(16));
    $_SESSION['forms'] ??= [];
    while (count($_SESSION['forms']) >= 20) array_shift($_SESSION['forms']);
    $_SESSION['forms'][$submission] = ['reference' => $reference, 'result' => null];
}
echo json_encode(['authenticated' => true, 'csrf' => csrf(), 'submission' => $submission]);
