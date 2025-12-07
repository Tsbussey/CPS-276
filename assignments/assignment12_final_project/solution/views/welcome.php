<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/security.php'; require_login();
render_page('Welcome', function () {
  $name=htmlspecialchars($_SESSION['user']['name']??''); ?>
  <h1 class="display-5">Welcome Page</h1>
  <p class="lead">Welcome <?= $name ?></p>
<?php });
