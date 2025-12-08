<?php
// ==============================
// file: solution/views/loginForm.php
// ==============================
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../controllers/loginProc.php';

$msg = null; // only for "Login credentials incorrect"
$data = ['email' => ''];           // sticky (do not stick password)
$errors = ['email' => '', 'password' => '']; // inline field errors

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Gather inputs
  $data['email'] = trim($_POST['email'] ?? '');
  $pass          = trim($_POST['password'] ?? '');

  // Field validation (inline messages)
  if ($data['email'] === '' || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'You must enter a valid email.';
  }
  if ($pass === '') {
    $errors['password'] = 'You must enter a valid password.';
  }

  // If no field errors, attempt login
  if ($errors['email'] === '' && $errors['password'] === '') {
    $result = handle_login(['email' => $data['email'], 'password' => $pass]);

    if (!empty($result['ok'])) {
      header('Location: index.php?page=welcome');
      exit;
    } else {
      // Only show this when credentials are provided but wrong
      $msg = 'Login credentials incorrect';
    }
  }
}

function err(string $k, array $errors): string {
  return $errors[$k] !== '' ? '<div class="text-danger small mt-1">'.htmlspecialchars($errors[$k]).'</div>' : '';
}

render_page('Login', function () use (&$data, &$errors, $msg) { ?>
  <h1 class="mb-3">Login</h1>

  <?php if ($msg): ?>
    <div class="mb-3">Login credentials incorrect</div>
  <?php endif; ?>

  <form method="post" novalidate>
    <div class="mb-3">
      <label class="form-label" for="email">Email</label>
      <input class="form-control" type="text" name="email" id="email"
             value="<?= htmlspecialchars($data['email']) ?>">
      <?= err('email', $errors) ?>
    </div>
    <div class="mb-3">
      <label class="form-label" for="password">Password</label>
      <input class="form-control" type="password" name="password" id="password">
      <?= err('password', $errors) ?>
    </div>
    <button class="btn btn-primary">Login</button>
  </form>
<?php }); ?>
