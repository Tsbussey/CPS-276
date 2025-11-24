<?php

header('Content-Type: application/json; charset=utf-8');
ob_start();

require_once __DIR__.'/../classes/Db_conn.php';

function ok(array $p = []) { ob_end_clean(); echo json_encode(['masterstatus'=>'success'] + $p); exit; }
function err(string $m)    { ob_end_clean(); echo json_encode(['masterstatus'=>'error','msg'=>$m]); exit; }

try {
  $pdo = (new DatabaseConn())->dbOpen();
  $pdo->exec("DELETE FROM names");

  try { $pdo->exec("ALTER TABLE names AUTO_INCREMENT = 1"); } catch (Throwable $ignored) {}

  ok(['msg' => 'All names cleared.']);

} catch (Throwable $e) {
  err('Database error clearing names.');
}
