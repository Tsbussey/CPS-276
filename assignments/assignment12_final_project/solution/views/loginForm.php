<?php
// ==============================
// file: solution/views/loginForm.php
// (Bootstrap-only; matches requested behavior)
// ==============================
require_once __DIR__ . '/../controllers/loginProc.php';
render_page('Login', function () {
  $emailVal  = '';
  $errors    = ['email' => '', 'password' => ''];
  $showBanner = false;

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emailVal = trim($_POST['email'] ?? '');
    $pwdVal   = (string)($_POST['password'] ?? '');
    if ($emailVal === '' || !filter_var($emailVal, FILTER_VALIDATE_EMAIL)) { $errors['email'] = 'You must enter a valid email'; }
    if ($pwdVal === '') { $errors['password'] = 'You must enter a password'; }
    if ($errors['email'] === '' && $errors['password'] === '') {
      $res = handle_login();
      if ($res['ok']) { header('Location: index.php?page=welcome'); exit; }
      $showBanner = true; // only this line should show for wrong creds
    }
  }

  $emailInvalid    = $errors['email']    ? ' is-invalid' : '';
  $passwordInvalid = $errors['password'] ? ' is-invalid' : '';
  ?>
  <div class="row">
    <div class="col-12 col-md-10 col-lg-8 mx-auto">
      <h1 class="display-5 fw-bold mb-2">Login</h1>
      <?php if ($showBanner): ?>
        <p class="mb-3">Login credentials incorrect</p>
      <?php else: ?>
        <p class="text-muted mb-3">Enter your credentials to continue.</p>
      <?php endif; ?>

      <form method="post" novalidate>
        <div class="mb-3">
          <label class="form-label" for="email">Email</label>
          <input type="email" class="form-control<?= $emailInvalid ?>" id="email" name="email" value="<?= htmlspecialchars($emailVal) ?>">
          <?php if ($errors['email']): ?><div class="invalid-feedback d-block"><?= htmlspecialchars($errors['email']) ?></div><?php endif; ?>
        </div>
        <div class="mb-4">
          <label class="form-label" for="password">Password</label>
          <input type="password" class="form-control<?= $passwordInvalid ?>" id="password" name="password">
          <?php if ($errors['password']): ?><div class="invalid-feedback d-block"><?= htmlspecialchars($errors['password']) ?></div><?php endif; ?>
        </div>
        <button class="btn btn-primary">Login</button>
      </form>
    </div>
  </div>
  <?php
});
