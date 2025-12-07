<?php
declare(strict_types=1);
require_once __DIR__ . '/../classes/PdoMethods.php';
function insert_admin(string $name, string $email, string $hash, string $status): string {
  $pdo = new PdoMethods();
  $sql = "INSERT INTO admins (name,email,password,status) VALUES (:name,:email,:password,:status)";
  return $pdo->otherBinded($sql, [
    [":name",$name,"str"],[":email",$email,"str"],[":password",$hash,"str"],[":status",$status,"str"]
  ]);
}
function email_exists(string $email): bool {
  $pdo = new PdoMethods();
  $res = $pdo->selectBinded("SELECT id FROM admins WHERE email = :email", [[":email",$email,"str"]]);
  return $res !== 'error' && count($res) > 0;
}
