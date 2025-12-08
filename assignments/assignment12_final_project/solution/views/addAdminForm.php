<?php
// ==============================
// file: solution/views/addAdminForm.php
// ==============================
declare(strict_types=1);

require_once __DIR__ . '/../includes/security.php';
require_login();

require_once __DIR__ . '/../classes/Pdo_methods.php';

$pdo = new PdoMethods();

$ack  = null;   // success message
$msg  = null;   // generic DB/unexpected error
$dup  = false;  // duplicate email flag

// Sticky values
$data = [
  'fname'    => '',
  'lname'    => '',
  'email'    => '',
  'password' => '',
  'status'   => '',
];

// Field-level errors
$errors = [
  'fname'    => '',
  'lname'    => '',
  'email'    => '',
  'password' => '',
  'status'   => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Collect inputs
  $data['fname']    = trim($_POST['fname'] ?? '');
  $data['lname']    = trim($_POST['lname'] ?? '');
  $data['email']    = trim($_POST['email'] ?? '');
  $data['password'] = trim($_POST['password'] ?? '');
  $data['status']   = trim($_POST['status'] ?? '');

  // Basic validation -> set per-field messages
  $valid = true;

  if ($data['fname'] === '') {
    $valid = false;
    $errors['fname'] = 'You must enter a valid first name';
  }

  if ($data['lname'] === '') {
    $valid = false;
    $errors['lname'] = 'You must enter a valid last name';
  }

  if ($data['email'] === '' || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    $valid = false;
    $errors['email'] = 'You must enter a valid email.';
  }

  if ($data['password'] === '') {
    $valid = false;
    $errors['password'] = 'You must enter a valid password.';
  }

  if ($data['status'] === '' || !in_array($data['status'], ['admin', 'staff'], true)) {
    $valid = false;
    $errors['status'] = 'You must select a status.';
  }

  if ($valid) {
    try {
      // duplicate?
      $sql = "SELECT 1 FROM admins WHERE email = :email LIMIT 1";
      $res = $pdo->selectBinded($sql, [[":email", $data['email'], "str"]]);

      if ($res === 'error') {
        $msg = 'There was an error checking the email.';
      } elseif (!empty($res)) {
        // duplicate found
        $dup = true;
      } else {
        // insert
        $hash = password_hash($data['password'], PASSWORD_DEFAULT);
        $ins = "INSERT INTO admins (fname, lname, email, password, status)
                VALUES (:fname, :lname, :email, :password, :status)";
        $bind = [
          [":fname",    $data['fname'],    "str"],
          [":lname",    $data['lname'],    "str"],
          [":email",    $data['email'],    "str"],
          [":password", $hash,             "str"],
          [":status",   $data['status'],   "str"],
        ];
        $r = $pdo->otherBinded($ins, $bind);

        if ($r === 'noerror') {
          $ack = 'Administrator added';
          // clear sticky values after success
          $data = ['fname' => '', 'lname' => '', 'email' => '', 'password' => '', 'status' => ''];
        } else {
          $msg = 'There was an error adding the administrator.';
        }
      }
    } catch (Throwable $e) {
      $msg = 'There was an unexpected error.';
    }
  }
}

render_page('Add Admin', function () use (&$data, $ack, $msg, $dup, $errors) {
?>
  <!-- Hide the caret but keep the select fully functional -->
  <style>
    .no-caret {
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
      background-image: none !important;
    }
  </style>

  <?php if ($ack): ?>
    <div class="mb-3">Administrator added</div>
  <?php endif; ?>

  <?php if ($dup): ?>
    <!-- Black (default) text for duplicate warning -->
    <div class="mb-3">Someone with that email already exists</div>
  <?php endif; ?>

  <?php if ($msg && !$dup && !$ack): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>

  <h1 class="mb-3">Add Admin</h1>

  <form method="post" novalidate>
    <div class="row g-3">
      <!-- First Name -->
      <div class="col-md-6">
        <label for="fname" class="form-label">First Name</label>
        <input type="text" class="form-control" id="fname" name="fname"
               value="<?= htmlspecialchars($data['fname']) ?>">
        <?php if ($errors['fname']): ?>
          <div class="text-danger small mt-1"><?= htmlspecialchars($errors['fname']) ?></div>
        <?php endif; ?>
      </div>

      <!-- Last Name -->
      <div class="col-md-6">
        <label for="lname" class="form-label">Last Name</label>
        <input type="text" class="form-control" id="lname" name="lname"
               value="<?= htmlspecialchars($data['lname']) ?>">
        <?php if ($errors['lname']): ?>
          <div class="text-danger small mt-1"><?= htmlspecialchars($errors['lname']) ?></div>
        <?php endif; ?>
      </div>

      <!-- Email -->
      <div class="col-md-6">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email"
               value="<?= htmlspecialchars($data['email']) ?>">
        <?php if ($errors['email']): ?>
          <div class="text-danger small mt-1"><?= htmlspecialchars($errors['email']) ?></div>
        <?php endif; ?>
      </div>

      <!-- Password -->
      <div class="col-md-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password"
               value="<?= htmlspecialchars($data['password']) ?>">
        <?php if ($errors['password']): ?>
          <div class="text-danger small mt-1"><?= htmlspecialchars($errors['password']) ?></div>
        <?php endif; ?>
      </div>

      <!-- Status -->
      <div class="col-md-3">
        <label for="status" class="form-label">Status</label>
        <select id="status" name="status" class="form-select no-caret">
          <option value="">Please Select a Status</option>
          <option value="admin" <?= $data['status'] === 'admin' ? 'selected' : '' ?>>Admin</option>
          <option value="staff" <?= $data['status'] === 'staff' ? 'selected' : '' ?>>Staff</option>
        </select>
        <?php if ($errors['status']): ?>
          <div class="text-danger small mt-1"><?= htmlspecialchars($errors['status']) ?></div>
        <?php endif; ?>
      </div>

      <div class="col-12">
        <button class="btn btn-primary">Add Admin</button>
      </div>
    </div>
  </form>
<?php
});
