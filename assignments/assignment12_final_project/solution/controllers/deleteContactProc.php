<?php
// ======================================================================
// file: solution/controllers/deleteContactProc.php  (drop-in)
// ======================================================================
declare(strict_types=1);

require_once __DIR__ . '/../classes/Db_conn.php';

function process_delete_contacts() {
  if (empty($_POST['ids'])) return 'noop';
  $ids = array_map('intval', $_POST['ids']);
  $ids = array_values(array_filter($ids, fn($v)=>$v>0));
  if (!$ids) return 'noop';

  $in = implode(',', array_fill(0, count($ids), '?'));

  try {
    $pdo = (new Db_conn())->dbOpen();
    // NOTE: if your actual table name differs, change `contacts` here.
    $stmt = $pdo->prepare("DELETE FROM contacts WHERE id IN ($in)");
    foreach ($ids as $i => $id) { $stmt->bindValue($i+1, $id, PDO::PARAM_INT); }
    $stmt->execute();
    return 'success';
  } catch (Throwable $e) {
    // why: DB/table issues should not hard-crash the page
    return 'error';
  }
}
