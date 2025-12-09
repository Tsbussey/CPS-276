<?php
// ==============================
// file: solution/views/welcome.php
// ==============================
declare(strict_types=1);

require_once __DIR__ . '/../includes/security.php';
require_login();

$fname = $_SESSION['user']['fname'] ?? '';
$lname = $_SESSION['user']['lname'] ?? '';

/** Fallback to DB if names are missing in session */
if (trim($fname . $lname) === '') {
  require_once __DIR__ . '/../classes/Pdo_methods.php';
  $pdo = new PdoMethods();

  $id    = $_SESSION['user']['id']    ?? null;
  $email = $_SESSION['user']['email'] ?? null;

  $row = null;
  if ($id) {
    $sql = "SELECT fname, lname FROM admins WHERE id = :id LIMIT 1";
    $res = $pdo->selectBinded($sql, [[":id", (int)$id, "int"]]);
    if ($res !== 'error' && !empty($res)) { $row = $res[0]; }
  }
  if (!$row && $email) {
    $sql = "SELECT fname, lname FROM admins WHERE email = :email LIMIT 1";
    $res = $pdo->selectBinded($sql, [[":email", (string)$email, "str"]]);
    if ($res !== 'error' && !empty($res)) { $row = $res[0]; }
  }

  if ($row) {
    $fname = (string)($row['fname'] ?? '');
    $lname = (string)($row['lname'] ?? '');
    // update session so future requests don’t have to hit DB
    $_SESSION['user']['fname'] = $fname;
    $_SESSION['user']['lname'] = $lname;
  }
}

$display = trim($fname . ' ' . $lname);
if ($display === '') { $display = 'User'; }

render_page('Welcome Page', function () use ($display) { ?>
  <h1 class="mb-3">Welcome Page</h1>
  <p>Welcome <?= htmlspecialchars($display) ?></p>
<?php });
