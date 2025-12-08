<?php
// ==============================
// file: solution/controllers/addContactProc.php
// ==============================
set_include_path(__DIR__ . '/../classes' . PATH_SEPARATOR . get_include_path());
require_once __DIR__ . '/../classes/Pdo_methods.php';

function insert_contact($data){
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
