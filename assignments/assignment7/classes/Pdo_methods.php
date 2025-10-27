<?php
require_once __DIR__ . '/Db_conn.php';

/**
 * WHY: small helpers for prepared queries; keeps pages slim.
 */
class PdoMethods extends DatabaseConn {

  /** Run SELECT; returns array or 'error' */
  public function select(string $sql, array $bindings): array|string {
    try {
      $pdo = $this->dbOpen();
      $stmt = $pdo->prepare($sql);
      foreach ($bindings as $b) {
        [$ph, $val, $type] = $b;
        $stmt->bindValue($ph, $val, $this->typeMap($type));
      }
      $stmt->execute();
      return $stmt->fetchAll();
    } catch (Throwable $t) {
      return 'error';
    }
  }

  /** Run INSERT/UPDATE/DELETE; returns rowCount or 'error' */
  public function otherBinded(string $sql, array $bindings): int|string {
    try {
      $pdo = $this->dbOpen();
      $stmt = $pdo->prepare($sql);
      foreach ($bindings as $b) {
        [$ph, $val, $type] = $b;
        $stmt->bindValue($ph, $val, $this->typeMap($type));
      }
      $stmt->execute();
      return $stmt->rowCount();
    } catch (Throwable $t) {
      return 'error';
    }
  }

  private function typeMap(string $type): int {
    return match ($type) {
      'int'  => PDO::PARAM_INT,
      'bool' => PDO::PARAM_BOOL,
      'null' => PDO::PARAM_NULL,
      default => PDO::PARAM_STR,
    };
  }
}
