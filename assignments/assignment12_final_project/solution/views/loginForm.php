<?php
declare(strict_types=1);
require_once __DIR__ . '/../controllers/loginProc.php';

render_page('Login', function () {
  // Sticky + validation state
  $emailVal   = '';
  $errors     = ['email' => '', 'password' => ''];
  $bannerMsg  = '';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic server-side validation (Bootstrap only; no JS)
    $emailVal = trim($_POST['email'] ?? '');
    $pwdVal   = (string)($_POST['password'] ?? '');

    if ($emailVal === '') {
      $errors['email'] = 'You must enter a valid email';
    } elseif (!filter_var($emailVal, FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = 'You must enter a valid email';
    }
    if ($pwdVal === '') {
      $errors['password'] = 'You must enter a password';
    }

    // Only hit DB when fields are valid
    if ($errors['email'] === '' && $errors['password'] === '') {
      $res = handle_login();
      if ($res['ok']) {
        header("Location: index.php?page=welcome");
        exit;
      } else {
        // Match the wording from the instructor’s sample
        $bannerMsg = 'Login credentials incorrect';
      }
    }
  }

  // Helpers for Bootstrap invalid styles
  $emailInvalid   = $errors['email']   ? ' is-invalid' : '';
  $passwordInvalid= $errors['password']? ' is-invalid' : '';
  ?>
  <div class="row justify-content-center">
    <div class="col-12 col-md-6">
      <h1 class="h3 mb-3">Login</h1>

      <?php if ($bannerMsg): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($bannerMsg) ?></div>
      <?php endif; ?>

      <form method="post" novalidate>
        <div class="mb-3">
          <label class="form-label" for="email">Email</label>
          <input
            type="email"
            class="form-control<?= $emailInvalid ?>"
            id="email"
            name="email"
            value="<?= htmlspecialchars($emailVal) ?>"
          >
          <?php if ($errors['email']): ?>
            <div class="invalid-feedback d-block"><?= htmlspecialchars($errors['email']) ?></div>
          <?php endif; ?>
        </div>

        <div class="mb-3">
          <label class="form-label" for="password">Password</label>
          <input
            type="password"
            class="form-control<?= $passwordInvalid ?>"
            id="password"
            name="password"
          >
          <?php if ($errors['password']): ?>
            <div class="invalid-feedback d-block"><?= htmlspecialchars($errors['password']) ?></div>
          <?php endif; ?>
        </div>

        <button class="btn btn-primary">Login</button>
      </form>
    </div>
  </div>
  <?php
});
