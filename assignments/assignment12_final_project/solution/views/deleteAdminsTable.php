<?php
// ======================================================================
// file: solution/views/deleteAdminsTable.php
// ======================================================================
declare(strict_types=1);

require_once __DIR__ . '/../includes/security.php';
require_login();
require_once __DIR__ . '/../classes/Db_conn.php';

// Same delete behavior you had
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete']) && !empty($_POST['ids'])) {
  $pdo = (new Db_conn())->dbOpen();
  $ids = array_map('intval', (array)$_POST['ids']);
  if ($ids) {
    $in  = implode(',', array_fill(0, count($ids), '?'));
    $stm = $pdo->prepare("DELETE FROM admins WHERE id IN ($in)");
    $stm->execute($ids);
  }
}

render_page('Delete Admin(s)', function () {
  $pdo  = (new Db_conn())->dbOpen();
  $stmt = $pdo->query("SELECT id, fname, lname, email, password, status
                       FROM admins
                       ORDER BY lname, fname");
  $rows = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
?>
  <h1 class="h3 mb-3">Delete Admin(s)</h1>

  <?php if (!$rows): ?>
    <div class="alert alert-secondary">There are no records to display</div>
  <?php else: ?>
    <form method="post">
      <div class="mb-2"><button class="btn btn-danger btn-sm" name="delete" value="1">Delete</button></div>
      <div class="table-responsive">
        <table class="table table-bordered table-striped table-sm align-middle">
          <thead class="table-light">
            <tr>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Email</th>
              <th>Password</th>
              <th>Status</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $r): ?>
              <tr>
                <td><?= htmlspecialchars($r['fname'] ?? '') ?></td>
                <td><?= htmlspecialchars($r['lname'] ?? '') ?></td>
                <td><?= htmlspecialchars($r['email'] ?? '') ?></td>
                <td style="max-width:520px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                  <?= htmlspecialchars($r['password'] ?? '') ?>
                </td>
                <td><?= htmlspecialchars($r['status'] ?? '') ?></td>
                <td class="text-center"><input type="checkbox" name="ids[]" value="<?= (int)$r['id'] ?>"></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </form>
  <?php endif; ?>
<?php
});
