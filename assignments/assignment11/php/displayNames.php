<?php
/* =========================================================================
   File: php/displayNames.php    (DROP-IN REPLACEMENT)
   ========================================================================= */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../classes/Pdo_methods.php';

function ok(array $payload = []) {
  echo json_encode(array_merge(['masterstatus' => 'success'], $payload));
  exit;
}
function err(string $msg) {
  echo json_encode(['masterstatus' => 'error', 'msg' => $msg]);
  exit;
}

try {
  $pdo  = new PdoMethods();
  $rows = $pdo->selectNotBinded("SELECT name FROM names ORDER BY name ASC");
  if ($rows === 'error') err('Database error fetching names.');

  if (!$rows || count($rows) === 0) {
    ok(['names' => 'No names to display']); // <- what your JS expects on empty
  } else {
    // Render with Bootstrap list-group (works with your JS .innerHTML)
    $html = '<ul class="list-group">';
    foreach ($rows as $r) {
      $safe = htmlspecialchars($r['name'] ?? '', ENT_QUOTES, 'UTF-8'); // why: XSS safety
      $html .= "<li class=\"list-group-item\">{$safe}</li>";
    }
    $html .= '</ul>';
    ok(['names' => $html]);
  }
} catch (Throwable $e) {
  err('Unexpected server error.');
}
