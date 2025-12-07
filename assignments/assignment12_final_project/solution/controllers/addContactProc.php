<?php
declare(strict_types=1);
require_once __DIR__ . '/../classes/PdoMethods.php';
function insert_contact(array $data): string {
  $pdo = new PdoMethods();
  $sql = "INSERT INTO contacts (fname,lname,address,city,state,phone,email,dob,contacts,age)
          VALUES (:fname,:lname,:address,:city,:state,:phone,:email,:dob,:contacts,:age)";
  $bindings = [
    [":fname",$data['fname'],"str"],[":lname",$data['lname'],"str"],
    [":address",$data['address'],"str"],[":city",$data['city'],"str"],
    [":state",$data['state'],"str"],[":phone",$data['phone'],"str"],
    [":email",$data['email'],"str"],[":dob",$data['dob'],"str"],
    [":contacts",$data['contacts'],"str"],[":age",$data['age'],"str"],
  ];
  return $pdo->otherBinded($sql,$bindings); // 'noerror' on success
}