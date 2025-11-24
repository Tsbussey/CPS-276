<?php

header('Content-Type: application/json; charset=utf-8');
ob_start(); 

require_once __DIR__ . '/../classes/Db_conn.php';

function ok(array $p = []) { ob_end_clean(); echo json_encode(['masterstatus'=>'success'] + $p); exit; }
function err(string $m)    { ob_end_clean(); echo json_encode(['masterstatus'=>'error','msg'=>$m]); exit; }

try {
  $pdo = (new DatabaseConn())->dbOpen();


  $pdo->exec("CREATE TABLE IF NOT EXISTS names (
      id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(100) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

  $stmt = $pdo->query("SELECT name FROM names ORDER BY name ASC");
  $rows = $stmt->fetchAll();

  if (!$rows || count($rows) === 0) {
    ok(['names' => 'No names to display']);  
  }

  $lines = [];
  foreach ($rows as $r) {
    $lines[] = htmlspecialchars($r['name'] ?? '', ENT_QUOTES, 'UTF-8'); 
  }
  $plain = implode('<br>', $lines); 

  ok(['names' => $plain]);
} catch (Throwable $e) {
  err('Database error fetching names.');
}
