/* =========================================================================
   classes/Db_conn.php   (EDIT the credentials below, nothing else)
   ========================================================================= */
<?php

class DatabaseConn {	
  private $conn;

  public function dbOpen(){
    try {
      // ----- FILL THESE IN -----
      $dbHost = 'localhost';        // usually 'localhost' on russet
      $dbName = 'tabussey';
      $dbUsr  = 'tabussey';
      $dbPass = 'ubfT2R5HYPHsVfY';
      // -------------------------

      $dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4";
      $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
      ];
      $this->conn = new PDO($dsn, $dbUsr, $dbPass, $options);
      return $this->conn;
    }
    catch(PDOException $e) {
      // WHY: your JS expects JSON; echoing here would corrupt responses.
      // Let callers handle errors; do not echo.
      throw $e;
    }
  }
}