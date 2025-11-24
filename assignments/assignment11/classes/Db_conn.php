<?php

class DatabaseConn {
  private $conn;

  public function dbOpen() {
    try {
  
      $dbHost = 'localhost';          
      $dbName = 'tabussey';  
      $dbUsr  = 'tabussey';
      $dbPass = 'ubfT2R5HYPHsVfY';
      $DB_CHARSET = 'utf8mb4';
  

      $dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4";
      $opts = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
      ];
      $this->conn = new PDO($dsn, $dbUsr, $dbPass, $opts);
      return $this->conn;
    } catch (PDOException $e) {
      
      throw $e;
    }
  }
}
