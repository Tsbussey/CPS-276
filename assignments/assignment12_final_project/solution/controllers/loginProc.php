<?php
// ==============================
// file: solution/controllers/loginProc.php
// (adds include_path; safe PDO fallback)
// ==============================
set_include_path(__DIR__ . '/../classes' . PATH_SEPARATOR . get_include_path());
require_once __DIR__ . '/../classes/Pdo_methods.php';
require_once __DIR__ . '/../classes/Db_conn.php';

function handle_login(){
  $email = trim($_POST['email'] ?? '');
  $password = (string)($_POST['password'] ?? '');
  if ($email === '' || $password === '') return ['ok'=>false,'msg'=>'Invalid credentials'];

  $sql = "SELECT id,name,email,password,status FROM admins WHERE email = :email LIMIT 1";
  $bindings = [[":email",$email,"str"]];
  $row = null;

  try {
    $pdoMethods = new PdoMethods();
    if (method_exists($pdoMethods, 'selectBinded')) {
      $res = $pdoMethods->selectBinded($sql, $bindings);
      if ($res !== 'error' && !empty($res)) { $row = $res[0]; }
    } else {
      $pdo = (new Db_conn())->dbOpen();
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':email', $email, PDO::PARAM_STR);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
  } catch (Throwable $e) {
    return ['ok'=>false,'msg'=>'Invalid credentials'];
  }

  if (!$row) return ['ok'=>false,'msg'=>'Invalid credentials'];
  if (!password_verify($password, $row['password'])) return ['ok'=>false,'msg'=>'Invalid credentials'];

  $_SESSION['user'] = ['id'=>(int)$row['id'],'name'=>$row['name'],'email'=>$row['email'],'status'=>$row['status']];
  return ['ok'=>true];
}
