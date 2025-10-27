<?php
/**
 * DatabaseConn: single PDO factory for the app.
 * WHY: one place to set credentials, errors, charset, etc.
 */
class DatabaseConn {
  protected ?PDO $conn = null;

  // TODO: change these 4 values to your MySQL setup
  private string $host    = 'localhost';
  private string $db      = 'assignment7';
  private string $user    = 'your_user';
  private string $pass    = 'your_password';
  private string $charset = 'utf8mb4';

  protected function dbOpen(): PDO {
    if ($this->conn instanceof PDO) {
      return $this->conn;           // reuse same connection
    }
    $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
    $pdo = new PDO($dsn, $this->user, $this->pass, [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // WHY: fail fast, easier debugging
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false,                  // WHY: real prepared statements
    ]);
    $this->conn = $pdo;
    return $pdo;
  }
}
