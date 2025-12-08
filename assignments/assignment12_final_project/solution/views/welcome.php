<?php
// ==============================
// file: solution/views/welcome.php
// ==============================
require_once __DIR__ . '/../includes/security.php';
require_login();
render_page('Welcome', function () {
  $name = htmlspecialchars($_SESSION['user']['name'] ?? '');
  echo '<h1 class="display-5">Welcome Page</h1>';
  echo '<p class="lead">Welcome ' . $name . '</p>';
});
