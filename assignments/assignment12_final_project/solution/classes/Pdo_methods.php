<?php
require_once __DIR__ . '/Db_conn.php';  // <-- FIXED
class PdoMethods extends Db_conn{

  public function createUsersTableIfMissing() {
    $sql = "CREATE TABLE IF NOT EXISTS users (
              id INT AUTO_INCREMENT PRIMARY KEY,
              first_name VARCHAR(100) NOT NULL,
              last_name VARCHAR(100) NOT NULL,
              email VARCHAR(255) NOT NULL UNIQUE,
              password_hash VARCHAR(255) NOT NULL,
              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $pdo = $this->dbOpen();
    $stmt = null;
    try {
      $pdo->exec($sql);
    } catch (Exception $e) {
      error_log($e->getMessage());
    }
    $this->dbClose($pdo, $stmt);
  }

  public function selectOne($sql, $params = array()) {
    $pdo = $this->dbOpen();
    $stmt = null;
    try {
      $stmt = $pdo->prepare($sql);
      $stmt->execute($params);
      $row = $stmt->fetch();
      return $row ? $row : null;
    } catch (Exception $e) {
      error_log($e->getMessage());
      return null;
    }
    $this->dbClose($pdo, $stmt);
  }

  public function selectAll($sql, $params = array()) {
    $pdo = $this->dbOpen();
    $stmt = null;
    try {
      $stmt = $pdo->prepare($sql);
      $stmt->execute($params);
      return $stmt->fetchAll();
    } catch (Exception $e) {
      error_log($e->getMessage());
      return array();
    }
    $this->dbClose($pdo, $stmt);
  }

  public function execute($sql, $params = array()) {
    $pdo = $this->dbOpen();
    $stmt = null;
    try {
      $stmt = $pdo->prepare($sql);
      $stmt->execute($params);
      return $stmt->rowCount();
    } catch (Exception $e) {
      error_log($e->getMessage());
      return 0;
    }
    $this->dbClose($pdo, $stmt);
  }
}