<?php
// ==============================
// file: solution/views/deleteContactsTable.php
// ==============================
declare(strict_types=1);

require_once __DIR__ . '/../includes/security.php';
require_login();

require_once __DIR__ . '/../classes/Db_conn.php';

$pdo = (new Db_conn())->dbOpen();

/* Delete selected contacts (same UX as Admins) */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete']) && !empty($_POST['ids'])) {
  $ids = array_map('intval', (array)$_POST['ids']);
  if ($ids) {
    $ph  = implode(',', array_fill(0, count($ids), '?'));
    $stm = $pdo->prepare("DELETE FROM contacts WHERE id IN ($ph)");
    $stm->execute($ids);
  }
}

/* Helpers to match the teacher screenshots */
$fmtDob = static function (?string $dob): string {
  if (!$dob) return '';
  if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dob)) { [$y,$m,$d] = explode('-', $dob); }
  elseif (preg_match('#^\d{1,2}/\d{1,2}/\d{4}$#', $dob)) { [$m,$d,$y] = explode('/', $dob); }
  else { return htmlspecialchars($dob); }
  return (int)$m . '/' . (int)$d . '/' . $y; // no leading zeros
};

$stateMap = [
  'michigan'=>'MI','ohio'=>'OH','indiana'=>'IN','illinois'=>'IL','wisconsin'=>'WI',
];
$fmtState = static function (?string $state) use ($stateMap): string {
  if (!$state) return '';
  $s = trim($state);
  if (strlen($s) === 2) return strtoupper($s);
  $key = strtolower($s);
  return $stateMap[$key] ?? strtoupper($s);
};

render_page('Delete Contact(s)', function () use ($pdo, $fmtDob, $fmtState) {
  $stmt = $pdo->query(
    "SELECT id, fname, lname, address, city, state, phone, email, dob, contacts, age
     FROM contacts
     ORDER BY lname, fname"
  );
  $rows = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
  ?>
  <h1 class="h3 mb-3">Delete Contact(s)</h1>

  <?php if (!$rows): ?>
    <div class="alert alert-secondary">There are no records to display</div>
  <?php else: ?>
    <form method="post">
      <div class="mb-2">
        <button class="btn btn-danger btn-sm" name="delete" value="1">Delete</button>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered table-striped table-sm align-middle">
          <thead class="table-light">
            <tr>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Address</th>
              <th>City</th>
              <th>State</th>
              <th>Phone</th>
              <th>Email</th>
              <th>DOB</th>
              <th>Contact</th>
              <th>Age</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $r): ?>
              <tr>
                <td><?= htmlspecialchars($r['fname'] ?? '') ?></td>
                <td><?= htmlspecialchars($r['lname'] ?? '') ?></td>
                <td><?= htmlspecialchars($r['address'] ?? '') ?></td>
                <td><?= htmlspecialchars($r['city'] ?? '') ?></td>
                <td><?= htmlspecialchars($fmtState($r['state'] ?? '')) ?></td>
                <td><?= htmlspecialchars($r['phone'] ?? '') ?></td>
                <td><?= htmlspecialchars($r['email'] ?? '') ?></td>
                <td><?= $fmtDob($r['dob'] ?? '') ?></td>
                <td><?= htmlspecialchars($r['contacts'] ?? '') ?></td>
                <td><?= htmlspecialchars($r['age'] ?? '') ?></td>
                <td class="text-center">
                  <input type="checkbox" name="ids[]" value="<?= (int)$r['id'] ?>">
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </form>
  <?php endif; ?>
<?php });
