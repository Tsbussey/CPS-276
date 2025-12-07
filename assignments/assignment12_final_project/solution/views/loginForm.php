<?php
declare(strict_types=1);
require_once __DIR__ . '/../controllers/loginProc.php';
render_page('Login', function () {
  $msg='';
  if ($_SERVER['REQUEST_METHOD']==='POST') {
    $res=handle_login();
    if ($res['ok']) { header("Location: index.php?page=welcome"); exit; }
    $msg=$res['msg']??'Login failed';
  } ?>
  <div class="row justify-content-center"><div class="col-12 col-md-6">
    <h1 class="h3 mb-3">Login</h1>
    <?php if ($msg): ?><div class="alert alert-danger"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
    <form method="post" novalidate>
      <div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" name="email"></div>
      <div class="mb-3"><label class="form-label">Password</label><input type="password" class="form-control" name="password"></div>
      <button class="btn btn-primary">Login</button>
    </form>
  </div></div><?php
});
