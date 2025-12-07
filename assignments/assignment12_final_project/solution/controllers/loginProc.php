<?php
declare(strict_types=1);

set_include_path(__DIR__ . '/../classes' . PATH_SEPARATOR . get_include_path()); // make /classes resolvable
require_once __DIR__ . '/../classes/Pdo_methods.php';

function handle_login(): array {
  $pdo = new PdoMethods();
  $email = trim($_POST['email'] ?? '');
  $password = (string)($_POST['password'] ?? '');
  if ($email === '' || $password === '') return ['ok'=>false,'msg'=>'Invalid credentials'];
  $sql = "SELECT id,name,email,password,status FROM admins WHERE email = :email LIMIT 1";
  $res = $pdo->selectBinded($sql, [[":email",$email,"str"]]);
  if ($res === 'error' || count($res) === 0) return ['ok'=>false,'msg'=>'Invalid credentials'];
  $row = $res[0];
  if (!password_verify($password, $row['password'])) return ['ok'=>false,'msg'=>'Invalid credentials'];
  $_SESSION['user']=['id'=>(int)$row['id'],'name'=>$row['name'],'email'=>$row['email'],'status'=>$row['status']];
  return ['ok'=>true];
}
