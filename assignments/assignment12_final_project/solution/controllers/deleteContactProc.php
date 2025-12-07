<?php
declare(strict_types=1);
require_once __DIR__ . '/../classes/Db_conn.php';
function process_delete_contacts(): string {
  if (empty($_POST['ids'])) return 'noop';
  $ids = array_map('intval', $_POST['ids']); if (!$ids) return 'noop';
  $in = implode(',', array_fill(0, count($ids), '?'));
  try {
    $pdo = (new Db_conn())->dbOpen();
    $stmt = $pdo->prepare("DELETE FROM contacts WHERE id IN ($in)");
    foreach ($ids as $i => $id) $stmt->bindValue($i+1, $id, PDO::PARAM_INT);
    $stmt->execute(); return 'success';
  } catch (Throwable $e) { return 'error'; }
}
