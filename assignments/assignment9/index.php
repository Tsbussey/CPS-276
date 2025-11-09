<?php

declare(strict_types=1);

use App\Classes\StickyForm;
use App\Classes\Pdo_methods;

require_once __DIR__ . '/classes/Db_conn.php';
require_once __DIR__ . '/classes/Pdo_methods.php';
require_once __DIR__ . '/classes/Validation.php';
require_once __DIR__ . '/classes/StickyForm.php';

$form = new StickyForm();
$pdo  = new Pdo_methods();
$pdo->createUsersTableIfMissing();


$form->setDefaultValues([
  'first_name' => 'Ava',
  'last_name'  => "O'Brien",
  'email'      => 'ava.test@example.com',
  'password'   => 'Test@1234',
  'confirm'    => 'Test@1234',
]);

$rows = [];
$success = false;

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
  $form->resetErrors();

  $first = trim($_POST['first_name'] ?? '');
  $last  = trim($_POST['last_name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $pwd   = (string)($_POST['password'] ?? '');
  $conf  = (string)($_POST['confirm'] ?? '');

  $form->set('first_name', $first);
  $form->set('last_name',  $last);
  $form->set('email',      $email);
  $form->set('password',   '');
  $form->set('confirm',    '');

  if ($first === '' || !$form->validName($first)) {
    $form->setFieldError('first_name', "First name must contain only letters, spaces, or apostrophes.");
  }
  if ($last === '' || !$form->validName($last)) {
    $form->setFieldError('last_name', "Last name must contain only letters, spaces, or apostrophes.");
  }
  if ($email === '' || !$form->validEmail($email)) {
    $form->setFieldError('email', "Enter a valid email address.");
  }
  if ($pwd === '' || !$form->strongPassword($pwd)) {
    $form->setFieldError('password', "Password must be ≥8 chars with 1 uppercase, 1 number, and 1 symbol.");
  }
  if ($conf === '' || $conf !== $pwd) {
    $form->setFieldError('confirm', "Passwords must match.");
  }

  if (!$form->hasErrors()) {
    $existing = $pdo->selectOne("SELECT id FROM users WHERE email = ?", [$email]);
    if ($existing) {
      $form->setFieldError('email', "That email is already registered.");
    }
  }

  if (!$form->hasErrors()) {
    $hash = password_hash($pwd, PASSWORD_DEFAULT);
    $inserted = $pdo->execute(
      "INSERT INTO users (first_name, last_name, email, password_hash) VALUES (?, ?, ?, ?)",
      [$first, $last, $email, $hash]
    );
    if ($inserted > 0) {
      $success = true;
      $form->clearValues(); 
    }
  }
}

$rows = $pdo->selectAll("SELECT id, first_name, last_name, email, created_at FROM users ORDER BY id DESC");
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Assignment 9 — Sticky Registration Form</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { padding: 2rem; }
    .card { max-width: 760px; margin: 0 auto; }
    .required::after { content:" *"; color:#dc3545; }
    .records { max-width: 980px; margin: 2rem auto 0; }
  </style>
</head>
<body>
  <div class="card shadow-sm">
    <div class="card-body">
      <h1 class="h4 mb-3">User Registration</h1>

      <?php if ($success): ?>
        <div class="alert alert-success">Success! Record saved and fields cleared.</div>
      <?php endif; ?>

      <form method="post" novalidate>
        <div class="mb-3">
          <label for="first_name" class="form-label required">First Name</label>
          <input id="first_name" name="first_name" class="form-control" value="<?= $form->get('first_name') ?>">
          <?= $form->errorFor('first_name') ?>
        </div>

        <div class="mb-3">
          <label for="last_name" class="form-label required">Last Name</label>
          <input id="last_name" name="last_name" class="form-control" value="<?= $form->get('last_name') ?>">
          <?= $form->errorFor('last_name') ?>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label required">Email</label>
          <input id="email" name="email" type="email" class="form-control" value="<?= $form->get('email') ?>">
          <?= $form->errorFor('email') ?>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label required">Password</label>
          <input id="password" name="password" type="password" class="form-control" value="">
          <?= $form->errorFor('password') ?>
        </div>

        <div class="mb-3">
          <label for="confirm" class="form-label required">Confirm Password</label>
          <input id="confirm" name="confirm" type="password" class="form-control" value="">
          <?= $form->errorFor('confirm') ?>
        </div>

        <button class="btn btn-primary">Register</button>
      </form>
    </div>
  </div>

  <div class="records">
    <h2 class="h5 mb-3">All Records</h2>
    <?php if (empty($rows)): ?>
      <div class="alert alert-info">No records yet.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead>
            <tr>
              <th scope="col" style="width:80px;">ID</th>
              <th scope="col">First</th>
              <th scope="col">Last</th>
              <th scope="col">Email</th>
              <th scope="col" style="width:200px;">Created</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><?= (int)$r['id'] ?></td>
              <td><?= htmlspecialchars($r['first_name']) ?></td>
              <td><?= htmlspecialchars($r['last_name']) ?></td>
              <td><?= htmlspecialchars($r['email']) ?></td>
              <td><?= htmlspecialchars($r['created_at']) ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

</body>
</html>