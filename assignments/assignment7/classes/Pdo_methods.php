<?php
require_once __DIR__ . '/Db_conn.php';

class PdoMethods extends DatabaseConn {
  private PDO $pdo;

  public function __construct() { $this->pdo = $this->dbOpen(); }

  public function otherBinded(string $sql, array $bindings): int|string {
    try {
      $stmt = $this->pdo->prepare($sql);
      foreach ($bindings as $k => $v) {
        if ($k === '' || $k[0] !== ':') { throw new InvalidArgumentException("Binding keys must start with ':' (bad key: {$k})"); }
        $stmt->bindValue($k, $v);
      }
      $stmt->execute();
      return preg_match('/^\s*INSERT/i', $sql) ? $this->pdo->lastInsertId() : $stmt->rowCount();
    } catch (Throwable $e) {
      throw new RuntimeException('DB ERROR (otherBinded): ' . $e->getMessage());
    }
  }

  public function selectBinded(string $sql, array $bindings = []): array {
    try {
      $stmt = $this->pdo->prepare($sql);
      foreach ($bindings as $k => $v) {
        if ($k !== '' && $k[0] !== ':') { throw new InvalidArgumentException("Binding keys must start with ':' (bad key: {$k})"); }
        $stmt->bindValue($k, $v);
      }
      $stmt->execute();
      return $stmt->fetchAll();
    } catch (Throwable $e) {
      throw new RuntimeException('DB ERROR (selectBinded): ' . $e->getMessage());
    }
  }
}