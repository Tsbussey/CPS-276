<?php
// ==============================
// file: solution/controllers/loginProc.php
// ==============================

// make sure PHP can see the classes folder
set_include_path(__DIR__ . '/../classes' . PATH_SEPARATOR . get_include_path());

// load the PDO wrapper (book file name: Pdo_methods.php)
require_once __DIR__ . '/../classes/Pdo_methods.php';

/**
 * Compatibility shim:
 * If the class inside Pdo_methods.php is named PdoMethods (camel case),
 * create an alias so code that expects Pdo_methods still works.
 */
if (class_exists('PdoMethods') && !class_exists('Pdo_methods')) {
  class_alias('PdoMethods', 'Pdo_methods');
}

function handle_login(array $post): array {
  echo'handle_login';

  $email = trim($post['email'] ?? '');
  $pass  = (string)($post['password'] ?? '');

  if ($email === '' || $pass === '') {
    return ['ok' => false, 'msg' => 'Login credentials incorrect'];
  }

  // Use the book’s PDO class (works whether the actual class is Pdo_methods or PdoMethods)
  $pdo = new Pdo_methods();

  // Your admins table fields: id, fname, lname, email, password, status
  $sql = "SELECT id, fname, lname, email, password, status
          FROM admins
          WHERE email = :email
          LIMIT 1";

  $rows = $pdo->selectBinded($sql, [[":email", $email, "str"]]);

  if ($rows === 'error' || empty($rows)) {
    return ['ok' => false, 'msg' => 'Login credentials incorrect'];
  }

  $row = $rows[0];

  // Accept either hashed or plain for this assignment
  $passwordMatches =
      password_verify($pass, $row['password']) || $pass === $row['password'];

  if (!$passwordMatches) {
    return ['ok' => false, 'msg' => 'Login credentials incorrect'];
  }

  // Success: set session
   // session_regenerate_id(true); // best practice (optional)
  if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
  $_SESSION['auth']  = true;
  $_SESSION['uid']   = (int)$row['id'];
  $_SESSION['name']  = trim($row['fname'] . ' ' . $row['lname']);
  $_SESSION['email'] = $row['email'];
  $_SESSION['role']  = $row['status']; // 'admin' or 'staff'
  $_SESSION['user'] = (int)$row['id']; //Need this or you are toast. WTh.

  return ['ok' => true, 'msg' => ''];
}

/*
Q3) How are sessions used for authentication here?
A3) After verifying credentials against the admins table, I start a session
    and set these keys:
      - $_SESSION['auth']  = true   (router can tell you’re logged in)
      - $_SESSION['uid']   = id     (user id for later lookups)
      - $_SESSION['name']  = name   (display on welcome page)
      - $_SESSION['email'] = email  (current identity)
      - $_SESSION['role']  = status ('admin' or 'staff') → drives nav + guards
    These values persist via the session cookie across page requests until Logout.

example:

    Point at the session writes after password match:

if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
$_SESSION['auth']  = true;                                  // logged-in flag
$_SESSION['uid']   = (int)$row['id'];                       // user id
$_SESSION['name']  = trim($row['fname'] . ' ' . $row['lname']); // display
$_SESSION['email'] = $row['email'];                         // identity
$_SESSION['role']  = $row['status'];                        // 'admin'|'staff'
$_SESSION['user']  = (int)$row['id'];                       // legacy check used elsewhere

These values persist via the session cookie; router/nav read them on every request.
*/
