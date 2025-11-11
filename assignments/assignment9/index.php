<?php
require_once __DIR__ . '/classes/Db_conn.php';
require_once __DIR__ . '/classes/Pdo_methods.php';
require_once __DIR__ . '/classes/Validation.php';
require_once __DIR__ . '/classes/StickyForm.php';

$form = new StickyForm();
$pdo  = new Pdo_methods();
$pdo->createUsersTableIfMissing();

$isPost = ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST';

/* Prefill on initial load only */
$form->setDefaultValues([
  'first_name' => 'Scott',
  'last_name'  => 'Shaper',
  'email'      => 'sshaper@wccnet.edu',
  'password'   => 'Pass$or1',
  'confirm'    => 'Pass$or1',
]);

$success    = false;
$topMessage = '';

if ($isPost) {
  $form->resetErrors();

  $first = trim($_POST['first_name'] ?? '');
  $last  = trim($_POST['last_name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $pwd   = (string)($_POST['password'] ?? '');
  $conf  = (string)($_POST['confirm'] ?? '');

  // sticky values
  $form->set('first_name', $first);
  $form->set('last_name',  $last);
  $form->set('email',      $email);
  $form->set('password',   $pwd);
  $form->set('confirm',    $conf);

  // field validation
  if ($first === '' || !$form->validName($first)) $form->setFieldError('first_name',"First name must contain only letters, spaces, or apostrophes.");
  if ($last  === '' || !$form->validName($last))  $form->setFieldError('last_name',"Last name must contain only letters, spaces, or apostrophes.");
  if ($email === '' || !$form->validEmail($email)) $form->setFieldError('email',"Enter a valid email address.");

  // one error at a time between password/confirm
  if ($pwd === '' || !$form->strongPassword($pwd)) {
    $form->setFieldError('password',"Must have at least (8 characters, 1 uppercase, 1 symbol, 1 number)");
  } else if ($conf === '' || $conf !== $pwd) {
    $form->setFieldError('confirm',"your passwords do not match");
  }

  // dup email
  if (!$form->hasErrors()) {
    if ($pdo->selectOne("SELECT id FROM users WHERE email = ?", [$email])) {
      $form->setFieldError('email',"There is already a record with that email");
    }
  }

  // insert or re-render
  if (!$form->hasErrors()) {
    $hash = password_hash($pwd, PASSWORD_DEFAULT);
    if ($pdo->execute(
      "INSERT INTO users (first_name,last_name,email,password_hash) VALUES (?,?,?,?)",
      [$first,$last,$email,$hash]
    ) > 0) {
      $success    = true;
      $topMessage = "You have been added to the database";

      // clear ALL inputs after success (no reseed)
      $form->clearValues();
      $form->set('first_name','');
      $form->set('last_name','');
      $form->set('email','');
      $form->set('password','');
      $form->set('confirm','');
    }
  } else {
    // first error becomes the top message (plain text)
    $topMessage = $form->masterStatus['topMessage'] ?? $topMessage;
  }
}

$rows = $pdo->selectAll("SELECT first_name,last_name,email,password_hash FROM users ORDER BY id DESC");
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sticky Form Example</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .top-msg { margin-top: 2rem; margin-bottom: .75rem; }
    label { font-weight: 500; white-space: nowrap; }
    th { white-space: nowrap; }
  </style>
</head>
<body>
  <div class="container mt-5 pt-3">

    <?php if ($topMessage !== ''): ?>
      <p class="top-msg"><?php echo htmlspecialchars($topMessage); ?></p>
    <?php endif; ?>

    <form method="post" novalidate autocomplete="off">
      <div class="row g-3">
        <div class="col-12 col-md-6">
          <label for="first_name" class="form-label">First Name</label>
          <input id="first_name" name="first_name" class="form-control" value="<?php echo $form->get('first_name'); ?>">
        </div>
        <div class="col-12 col-md-6">
          <label for="last_name" class="form-label">Last Name</label>
          <input id="last_name" name="last_name" class="form-control" value="<?php echo $form->get('last_name'); ?>">
        </div>
      </div>

      <div class="row g-3 mt-0">
        <div class="col-12 col-md-4">
          <label for="email" class="form-label">Email</label>
          <input id="email" name="email" type="email" class="form-control" value="<?php echo $form->get('email'); ?>">
        </div>
        <div class="col-12 col-md-4">
          <label for="password" class="form-label">Password</label>
          <input id="password" name="password" class="form-control" type="text" autocomplete="off"
                 value="<?php echo htmlspecialchars($form->get('password')); ?>">
        </div>
        <div class="col-12 col-md-4">
          <label for="confirm" class="form-label">Confirm Password</label>
          <input id="confirm" name="confirm" class="form-control" type="text" autocomplete="off"
                 value="<?php echo htmlspecialchars($form->get('confirm')); ?>">
        </div>
      </div>

      <div class="mt-3 mb-2">
        <button class="btn btn-primary" type="submit">Register</button>
      </div>
    </form>

    <?php if (empty($rows)): ?>
      <p class="text-muted mt-2 mb-0">No records to display.</p>
    <?php else: ?>
      <table class="table table-bordered mt-2 mb-0">
        <thead>
          <tr>
            <th>First Name</th><th>Last Name</th><th>Email</th><th>Password</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><?php echo htmlspecialchars($r['first_name']); ?></td>
              <td><?php echo htmlspecialchars($r['last_name']); ?></td>
              <td><?php echo htmlspecialchars($r['email']); ?></td>
              <td><?php echo htmlspecialchars($r['password_hash']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</body>
</html>
