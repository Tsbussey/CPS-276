<?php
/**
 * Set your DB credentials here.
 * WHY: single source of truth for PDO connection.
 */
class DatabaseConn {
  protected ?PDO $conn = null;

<<<<<<< Updated upstream
  // TODO: change these 4 values to your MySQL setup
  private string $host    = 'localhost';
  private string $db      = 'assignment7';
  private string $user    = 'tabussey';
  private string $pass    = '*Gangwayforsaken1';
=======
  // EDIT THESE:
  private string $host = 'localhost';
  private string $db   = 'assignment7';
  private string $user = 'your_user';      // <-- change
  private string $pass = 'your_password';  // <-- change
>>>>>>> Stashed changes
  private string $charset = 'utf8mb4';

  protected function dbOpen(): PDO {
    if ($this->conn instanceof PDO) { return $this->conn; }
<<<<<<< Updated upstream
    $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
    $pdo = new PDO($dsn, $this->user, $this->pass, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    return $this->conn = $pdo;
  }
}
=======
    $cfg = require APP_PHP . '/config.php';
    $dsn = "mysql:host={$cfg['db_host']};dbname={$cfg['db_name']};charset={$cfg['db_charset']}";
    $pdo = new PDO($dsn, $cfg['db_user'], $cfg['db_pass'], [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
    return $this->conn = $pdo;
  }
}

>>>>>>> Stashed changes
