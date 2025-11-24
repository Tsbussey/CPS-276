<?php
header('Content-Type: application/json; charset=utf-8');
ob_start(); // WHY: swallow any accidental echoes/notices
require_once __DIR__ . '/../classes/Pdo_methods.php';

function ok(array $p = []) { ob_end_clean(); echo json_encode(['masterstatus'=>'success'] + $p); exit; }
function err(string $m)    { ob_end_clean(); echo json_encode(['masterstatus'=>'error','msg'=>$m]); exit; }

try {
  $pdo  = new PdoMethods();
  $rows = $pdo->selectNotBinded("SELECT name FROM names ORDER BY name ASC");
  if ($rows === 'error') err('Database error fetching names.');

  if (!$rows || count($rows) === 0) {
    ok(['names' => 'No names to display']);
  }

  $html = '<ul class="list-group">';
  foreach ($rows as $r) {
    $safe = htmlspecialchars($r['name'] ?? '', ENT_QUOTES, 'UTF-8'); // WHY: XSS safety
    $html .= "<li class=\"list-group-item\">{$safe}</li>";
  }
  $html .= '</ul>';
  ok(['names' => $html]);
}
catch (Throwable $e) { err('Unexpected server error.'); }