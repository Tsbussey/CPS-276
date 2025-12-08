<?php
// solution/classes/Db_conn.php
class Db_conn {
  private static ?PDO $pdo = null;

  public function dbOpen(): ?PDO {
    if (self::$pdo instanceof PDO) return self::$pdo;

    // Force TCP so MySQL uses password auth (not unix_socket)
    $dbHost = 'localhost';
    $dbName = 'tabussey';
    $dbUser = 'tabussey';        // or the new user they create, e.g. tabussey_web
    $dbPass = 'ubfT2R5HYPHsVfY';      // fill in AFTER they give you a password

    $dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4";
    $opts = [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
      self::$pdo = new PDO($dsn, $dbUser, $dbPass, $opts);
      return self::$pdo;
    } catch (PDOException $e) {
      echo 'DB Connection Error: '.$e->getMessage();
      return null;
    }
  }
}
