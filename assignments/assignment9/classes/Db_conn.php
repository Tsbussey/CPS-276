<?php
// ==============================
// file: solution/classes/Db_conn.php
// (book class; set your DB creds below)
// ==============================
class Db_conn {
  private $conn;
  public function dbOpen(){
    try {
      $dbHost = 'localhost';
      $dbName = 'cps276_final'; // TODO: your DB name
      $dbUsr  = 'root';         // TODO: your DB user
      $dbPass = '';             // TODO: your DB password
      $this->conn = new PDO('mysql:host='.$dbHost.';dbname='.$dbName, $dbUsr, $dbPass);
      $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $this->conn->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
      $this->conn->setAttribute(PDO::ATTR_AUTOCOMMIT, true);
      $this->conn->setAttribute(PDO::MYSQL_ATTR_LOCAL_INFILE, true);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $this->conn;
    } catch(PDOException $e) { echo $e->getMessage(); }
  }
}
