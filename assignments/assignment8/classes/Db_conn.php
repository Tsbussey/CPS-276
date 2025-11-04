<?php
/* ========================================================================
 * File: ~/public_html/cps276/assignments/assignment8/classes/Db_conn.php
 * Purpose: Hardcoded PDO using YOUR russet MySQL creds.
 * ====================================================================== */
declare(strict_types=1);

final class Db_conn {
    public static function pdo(): PDO {
        static $pdo = null;
        if ($pdo instanceof PDO) return $pdo;

        // From your ~/.my.cnf screenshot
        $dbName = 'tabussey';
        $dbUser = 'tabussey';
        $dbPass = 'ubfT2R5HYPHsVfY';

        $dsn = "mysql:host=localhost;dbname={$dbName};charset=utf8mb4";
        $opts = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // show real errors
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        return $pdo = new PDO($dsn, $dbUser, $dbPass, $opts);
    }
}
