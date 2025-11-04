<?php
use PDO;
use PDOStatement;

class Pdo_methods
{
    private PDO $pdo;
    public function __construct(PDO $pdo) { $this->pdo = $pdo; }

    public function selectAll(string $sql, array $params = []): array
    {
        $stmt = $this->run($sql, $params);
        return $stmt->fetchAll() ?: [];
    }

    public function execute(string $sql, array $params = []): int
    {
        $stmt = $this->run($sql, $params);
        return $stmt->rowCount();
    }

    private function run(string $sql, array $params): PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue(is_int($k) ? $k + 1 : (str_starts_with((string)$k, ':') ? (string)$k : ':' . $k), $v);
        }
        $stmt->execute();
        return $stmt;
    }
}
