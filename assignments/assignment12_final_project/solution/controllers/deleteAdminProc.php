<?php
// ==============================
// file: solution/controllers/deleteAdminProc.php
// ==============================
require_once __DIR__ . '/../classes/Db_conn.php';

/**
* @return 'noop' | 'success' | 'error'
*/
function process_delete_admins() {
  if (empty($_POST['ids']) || !is_array($_POST['ids'])) return 'noop';
  $ids = array_values(array_filter(array_map('intval', $_POST['ids']), fn($v) => $v > 0));
  if (!$ids) return 'noop';

  try {
    $pdo = (new Db_conn())->dbOpen();
    $in  = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("DELETE FROM admins WHERE id IN ($in)");
    foreach ($ids as $i => $id) $stmt->bindValue($i+1, $id, PDO::PARAM_INT);
    $stmt->execute();
    return 'success';
  } catch (Throwable $e) {
    return 'error';
  }
}
