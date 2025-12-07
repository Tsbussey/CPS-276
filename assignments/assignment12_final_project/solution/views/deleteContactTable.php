<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/security.php'; require_login();
require_once __DIR__ . '/../classes/Db_conn.php';
require_once __DIR__ . '/../controllers/deleteContactProc.php';

$pdo = (new Db_conn())->dbOpen();
$msg = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $r = process_delete_contacts();
  if ($r === 'success') $msg = 'Contact(s) deleted';
  elseif ($r === 'error') $msg = 'Could not delete the contacts';
}

render_page('Delete Contact(s)', function () use ($pdo, $msg) {
  $rows = $pdo->query("SELECT * FROM contacts ORDER BY lname, fname")->fetchAll(PDO::FETCH_ASSOC);
  ?>
  <?php if ($msg): ?><div class="alert alert-info"><?= $msg ?></div><?php endif; ?>
  <h1 class="h3 mb-3">Delete Contact(s)</h1>
  <?php if (!$rows): ?>
    <div class="alert alert-secondary">There are no records to display</div>
  <?php else: ?>
    <form method="post">
      <div class="mb-2"><button class="btn btn-danger btn-sm">Delete</button></div>
      <div class="table-responsive">
        <table class="table table-sm align-middle">
          <thead><tr>
            <th>First Name</th><th>Last Name</th><th>Address</th><th>City</th><th>State</th><th>Phone</th><th>Email</th><th>DOB</th><th>Contact</th><th>Age</th><th>Delete</th>
          </tr></thead>
          <tbody>
            <?php foreach ($rows as $r): ?>
              <tr>
                <td><?= htmlspecialchars($r['fname']) ?></td>
                <td><?= htmlspecialchars($r['lname']) ?></td>
                <td><?= htmlspecialchars($r['address']) ?></td>
                <td><?= htmlspecialchars($r['city']) ?></td>
                <td><?= htmlspecialchars($r['state']) ?></td>
                <td><?= htmlspecialchars($r['phone']) ?></td>
                <td><?= htmlspecialchars($r['email']) ?></td>
                <td><?= htmlspecialchars($r['dob']) ?></td>
                <td><?= htmlspecialchars($r['contacts']) ?></td>
                <td><?= htmlspecialchars($r['age']) ?></td>
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
