<?php
// ==============================
// file: solution/views/loginForm.php
// ==============================
require_once __DIR__ . '/../includes/security.php';

require_once __DIR__ . '/../controllers/loginProc.php';

$msg = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $result = handle_login($_POST);

  if ($result['ok'] === true) {
    header('Location: index.php?page=welcome');
    exit;
  } else {
    // keep it as a simple string so the view never treats it like an array
    $msg = $result['msg'] ?? 'Login credentials incorrect';
  }
}

render_page('Login', function () use ($msg) { ?>
  <h1 class="mb-3">Login</h1>

  <?php if (!empty($msg)): ?>
    <div class="alert alert-warning"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>

  <form method="post" novalidate>
    <div class="mb-3">
      <label class="form-label" for="email">Email</label>
      <input class="form-control" type="email" name="email" id="email" required>
    </div>
    <div class="mb-3">
      <label class="form-label" for="password">Password</label>
      <input class="form-control" type="password" name="password" id="password" required>
    </div>
    <button class="btn btn-primary">Login</button>
  </form>
<?php });
