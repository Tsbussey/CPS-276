<?php
class DatabaseConn {	
  private $conn;

  /** Configure your DB connection here */
  public function dbOpen(){
    try {
      $dbHost = 'localhost';        // <- change
      $dbName = 'your_database';    // <- change
      $dbUsr  = 'your_username';    // <- change
      $dbPass = 'your_password';    // <- change

      $this->conn = new PDO("mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4", $dbUsr, $dbPass);
      $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->conn->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
      return $this->conn;
    }
    catch(PDOException $e) { 
      echo $e->getMessage(); 
      exit;
    }
  }
}
