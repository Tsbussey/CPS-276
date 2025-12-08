<?php
// ==============================
// file: solution/includes/security.php
// ==============================
function require_login() {
  if (empty($_SESSION['user'])) { header('Location: index.php?page=login'); exit; }
}
function require_admin() {
  require_login();
  if (($_SESSION['user']['status'] ?? '') !== 'admin') { header('Location: index.php?page=login'); exit; }
}
