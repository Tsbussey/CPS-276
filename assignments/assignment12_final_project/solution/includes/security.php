<?php
declare(strict_types=1);
function require_login(): void {
  if (!isset($_SESSION['user'])) { header("Location: index.php?page=login"); exit; }
}
function require_admin(): void {
  require_login();
  if (($_SESSION['user']['status'] ?? '') !== 'admin') { header("Location: index.php?page=login"); exit; }
}
