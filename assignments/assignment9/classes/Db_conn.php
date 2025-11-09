<?php

class Db_conn {
  protected $DB_HOST = 'localhost';
  protected $DB_NAME = 'tabussey';
  protected $DB_USER = 'tabussey';
  protected $DB_PASS =  'ubfT2R5HYPHsVfY';
  protected $DB_CHARSET = 'utf8mb4';

  protected function dbOpen() {
    $dsn = "mysql:host={$this->DB_HOST};dbname={$this->DB_NAME};charset={$this->DB_CHARSET}";
    $options = array(
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
    );
    try {
      return new PDO($dsn, $this->DB_USER, $this->DB_PASS, $options);
    } catch (Exception $e) {
      error_log("DB connection failed: " . $e->getMessage());
      die("Database connection failed.");
    }
  }

  protected function dbClose(&$pdo = null, &$stmt = null) {
    $stmt = null;
    $pdo = null;
  }
}

    $dbName = 'tabussey';
        $dbUser = 'tabussey';
        $dbPass = 'ubfT2R5HYPHsVfY';