<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/security.php'; require_admin();
require_once __DIR__ . '/../classes/Db_conn.php';
require_once __DIR__ . '/../controllers/deleteAdminProc.php';

$pdo = (new Db_conn())->dbOpen();
$msg = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $r = process_delete_admins();
  if ($r === 'success') $msg = 'Admin(s) deleted';
  elseif ($r === 'error') $msg = 'Could not delete the admins';
}

render_page('Delete Admin(s)', function () use ($pdo, $msg) {
  $rows = $pdo->query("SELECT id,name,email,status FROM admins ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
  ?>
  <?php if ($msg): ?><div class="alert alert-info"><?= $msg ?></div><?php endif; ?>
  <h1 class="h3 mb-3">Delete Admin(s)</h1>
  <?php if (!$rows): ?>
    <div class="alert alert-secondary">There are no records to display</div>
  <?php else: ?>
    <form method="post">
      <div class="mb-2"><button class="btn btn-danger btn-sm">Delete</button></div>
      <div class="table-responsive">
        <table class="table table-sm align-middle">
          <thead><tr><th>Name</th><th>Email</th><th>Status</th><th>Delete</th></tr></thead>
          <tbody>
            <?php foreach ($rows as $r): ?>
              <tr>
                <td><?= htmlspecialchars($r['name']) ?></td>
                <td><?= htmlspecialchars($r['email']) ?></td>
                <td><?= htmlspecialchars($r['status']) ?></td>
                <td><input type="checkbox" name="ids[]" value="<?= (int)$r['id'] ?>"></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </form>
  <?php endif; ?>
  <?php
});
