<?php

header('Content-Type: application/json; charset=utf-8');
ob_start();

require_once __DIR__.'/../classes/Db_conn.php';

function ok(array $p = []) { ob_end_clean(); echo json_encode(['masterstatus'=>'success'] + $p); exit; }
function err(string $m)    { ob_end_clean(); echo json_encode(['masterstatus'=>'error','msg'=>$m]); exit; }

try {
  $data = json_decode(file_get_contents('php://input'), true);
  if (!is_array($data) || !isset($data['name'])) err('Invalid request.');

  $raw = trim((string)$data['name']);
  $parts = preg_split('/\s+/', $raw, 2, PREG_SPLIT_NO_EMPTY);
  if (!$parts || count($parts) < 2) err('Enter first and last name separated by a space.');

  [$first, $last] = [trim($parts[0]), trim($parts[1])];
  if ($first === '' || $last === '') err('Enter first and last name separated by a space.');

  $formatted = "{$last}, {$first}";

  $pdo = (new DatabaseConn())->dbOpen();
  $stmt = $pdo->prepare("INSERT INTO names (name) VALUES (:name)");
  $stmt->execute([':name' => $formatted]);

  ok(['msg' => "Added: {$formatted}"]);

} catch (Throwable $e) {
  err('Database error adding name.');
}
