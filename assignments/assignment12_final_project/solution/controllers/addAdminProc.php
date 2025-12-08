<?php
// ==============================
// file: solution/controllers/addAdminProc.php
// ==============================
set_include_path(__DIR__ . '/../classes' . PATH_SEPARATOR . get_include_path());
require_once __DIR__ . '/../classes/Pdo_methods.php';

/**
 * Add an admin.
 * Returns:
 *   - 'noerror'   on success
 *   - 'duplicate' if email already exists
 *   - 'dberror'   if a DB error occurs
 */
function add_admin(array $data) {
  $pdo = new PdoMethods();

  // 1) Duplicate by email?
  $dupSql = "SELECT id FROM admins WHERE email = :email LIMIT 1";
  $dup = $pdo->selectBinded($dupSql, [[":email", $data['email'], "str"]]);
  if ($dup === 'error') { return 'dberror'; }
  if ($dup && count($dup) > 0) { return 'duplicate'; }

  // 2) Insert new admin (hash password)
  $insSql = "INSERT INTO admins (fname, lname, email, password, status)
             VALUES (:fname, :lname, :email, :password, :status)";
  $bindings = [
    [":fname",    $data['fname'],                      "str"],
    [":lname",    $data['lname'],                      "str"],
    [":email",    $data['email'],                      "str"],
    [":password", password_hash($data['password'], PASSWORD_DEFAULT), "str"],
    [":status",   $data['status'],                     "str"],
  ];
  return $pdo->otherBinded($insSql, $bindings); // returns 'noerror' on success
}

/** List all admins for Delete Admin(s) page */
function list_admins() {
  $pdo = new PdoMethods();
  $sql = "SELECT id, fname, lname, email, status
          FROM admins
          ORDER BY lname, fname";
  $rows = $pdo->selectNotBinded($sql);
  if ($rows === 'error' || $rows === null) return [];
  return $rows;
}
