<?php

namespace App\Classes;

use PDO;
use PDOException;

class Db_conn {

  private const DB_HOST = 'localhost';
  private const DB_NAME = 'tabussey'
  private const DB_USER = 'tabussey'
  private const DB_PASS = 'ubfT2R5HYPHsVfY'
  private const DB_CHARSET = 'utf8mb4';

  protected function dbOpen(): PDO {
    $dsn = "mysql:host=" . self::DB_HOST . ";dbname=" . self::DB_NAME . ";charset=" . self::DB_CHARSET;
    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
    ];
    try {
      return new PDO($dsn, self::DB_USER, self::DB_PASS, $options);
    } catch (PDOException $e) {

      error_log("DB connection failed: " . $e->getMessage());
      throw new PDOException("Database connection failed.");
    }
  }

  protected function dbClose(?PDO &$pdo = null, ?\PDOStatement &$stmt = null): void {
    $stmt = null;
    $pdo = null;
  }
}
