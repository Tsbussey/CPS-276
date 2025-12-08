<?php
// ==============================
// file: solution/controllers/loginProc.php
// ==============================
set_include_path(__DIR__ . '/../classes' . PATH_SEPARATOR . get_include_path());
require_once __DIR__ . '/../classes/Pdo_methods.php';

function handle_login(array $post): array {
  $msg = '';
  $ok  = false;

  $email = trim($post['email'] ?? '');
  $pass  = (string)($post['password'] ?? '');

  if ($email === '' || $pass === '') {
    return ['ok' => false, 'msg' => 'Login credentials incorrect'];
  }

  $pdo = new PdoMethods();
  // Your admins table uses: id, fname, lname, email, password, status
  $sql = "SELECT id, fname, lname, email, password, status
          FROM admins
          WHERE email = :email
          LIMIT 1";
  $rows = $pdo->selectBinded($sql, [[":email",$email,"str"]]);

  if ($rows === 'error' || empty($rows)) {
    return ['ok' => false, 'msg' => 'Login credentials incorrect'];
  }

  $row = $rows[0];

  // If you stored plain text passwords for the assignment, compare directly.
  // If you used password_hash(), keep the verify() line and remove the direct compare.
  $passwordMatches =
      password_verify($pass, $row['password']) || $pass === $row['password'];

  if (!$passwordMatches) {
    return ['ok' => false, 'msg' => 'Login credentials incorrect'];
  }

  // Success: set session
  if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
  $_SESSION['auth']  = true;
  $_SESSION['uid']   = (int)$row['id'];
  $_SESSION['name']  = trim($row['fname'] . ' ' . $row['lname']);
  $_SESSION['email'] = $row['email'];
  $_SESSION['role']  = $row['status']; // e.g., 'admin' or 'staff'

  return ['ok' => true, 'msg' => ''];
}
