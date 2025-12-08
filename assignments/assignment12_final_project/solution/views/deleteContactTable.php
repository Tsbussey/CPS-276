<?php
// ==============================
// file: solution/views/deleteContactsTable.php
// ==============================
declare(strict_types=1);

require_once __DIR__ . '/../includes/security.php'; require_login();
require_once __DIR__ . '/../classes/Db_conn.php';
require_once __DIR__ . '/../controllers/deleteContactProc.php';

$pdo = (new Db_conn())->dbOpen();
$msg = null;

// >>> If your real table is named something else, change it here only.
$TABLE = 'contacts';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $r = process_delete_contacts();
  if ($r === 'success') $msg = '<div class="alert alert-success">Contact(s) deleted</div>';
  elseif ($r === 'error') $msg = '<div class="alert alert-danger">Could not delete the contacts.</div>';
  elseif ($r === 'noop') $msg = '<div class="alert alert-secondary">Nothing selected.</div>';
}

render_page('Delete Contact(s)', function () use ($pdo, $msg, $TABLE) {
  $rows = [];
  $loadError = false;

  try {
    // select the same columns you display; order by last, first
    $sql = "SELECT id, fname, lname, address, city, state, phone, email, dob, contacts, age
            FROM {$TABLE} ORDER BY lname, fname";
    $rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC) ?: [];
  } catch (Throwable $e) {
    // Table missing or wrong name -> show friendly message instead of fatal
    $loadError = true;
  }
  ?>
  <?= $msg ?? '' ?>

  <?php if ($loadError): ?>
    <div class="alert alert-danger">
      Could not load contacts. Ensure the <code><?= htmlspecialchars($TABLE) ?></code> table exists,
      or update the <code>$TABLE</code> name at the top of this file to match your actual table.
    </div>
  <?php else: ?>
    <h1 class="h3 mb-3">Delete Contact(s)</h1>
    <?php if (!$rows): ?>
      <div class="alert alert-secondary">There are no records to display</div>
    <?php else: ?>
      <form method="post">
        <div class="mb-2"><button class="btn btn-danger btn-sm" name="delete" value="1">Delete</button></div>
        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead><tr>
              <th>First Name</th><th>Last Name</th><th>Address</th><th>City</th><th>State</th>
              <th>Phone</th><th>Email</th><th>DOB</th><th>Contact</th><th>Age</th><th>Delete</th>
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
                <td class="text-center"><input type="checkbox" name="ids[]" value="<?= (int)$r['id'] ?>"></td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </form>
    <?php endif; ?>
  <?php endif; ?>
  <?php
});
