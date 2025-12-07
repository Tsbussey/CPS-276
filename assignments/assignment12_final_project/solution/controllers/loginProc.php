<?php
declare(strict_types=1);

/* Make /classes resolvable for book requires like "Db_conn.php" */
set_include_path(__DIR__ . '/../classes' . PATH_SEPARATOR . get_include_path());

require_once __DIR__ . '/../classes/Pdo_methods.php'; // book file (underscore)
require_once __DIR__ . '/../classes/Db_conn.php';     // used for fallback

function handle_login(): array {
  $email = trim((string)($_POST['email'] ?? ''));
  $password = (string)($_POST['password'] ?? '');

  if ($email === '' || $password === '') {
    return ['ok' => false, 'msg' => 'Invalid credentials'];
  }

  $sql = "SELECT id,name,email,password,status FROM admins WHERE email = :email LIMIT 1";
  $row = null;

  try {
    $pdoMethods = new PdoMethods();

    if (method_exists($pdoMethods, 'selectBinded')) {
      $res = $pdoMethods->selectBinded($sql, [[":email", $email, "str"]]);
      if ($res !== 'error' && !empty($res)) { $row = $res[0]; }
    } else {
      // Fallback: do not modify book classes; use plain PDO if method is absent
      $pdo = (new Db_conn())->dbOpen();
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':email', $email, PDO::PARAM_STR);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
  } catch (Throwable $e) {
    return ['ok' => false, 'msg' => 'Invalid credentials'];
  }

  if (!$row) return ['ok' => false, 'msg' => 'Invalid credentials'];
  if (!password_verify($password, $row['password'])) return ['ok' => false, 'msg' => 'Invalid credentials'];

  $_SESSION['user'] = [
    'id'     => (int)$row['id'],
    'name'   => $row['name'],
    'email'  => $row['email'],
    'status' => $row['status'],
  ];
  return ['ok' => true];
}
