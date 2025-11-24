<?php
header('Content-Type: application/json; charset=utf-8');
ob_start();
require_once __DIR__ . '/../classes/Pdo_methods.php';

function ok(array $p = []) { ob_end_clean(); echo json_encode(['masterstatus'=>'success'] + $p); exit; }
function err(string $m)    { ob_end_clean(); echo json_encode(['masterstatus'=>'error','msg'=>$m]); exit; }

try {
  $pdo = new PdoMethods();
  $del = $pdo->otherNotBinded("DELETE FROM names");
  if ($del === 'error') err('Database error clearing names.');
  // Optional reset; ignore failure in shared envs
  $pdo->otherNotBinded("ALTER TABLE names AUTO_INCREMENT = 1");
  ok(['msg' => 'All names cleared.']);
}
catch (Throwable $e) { err('Unexpected server error.'); }