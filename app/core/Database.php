<?php

class Database {
    private static ?Database $instance = null;
    private ?PDO $pdo = null;

    private function __construct() {
        try {
            $this->pdo = new PDO(
                "pgsql:host=localhost;dbname=notes_ecole;port=5433",
                "ichigo",
                "password"
            );
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $ex) {
            die('Erreur de connexion à la base de données : ' . $ex->getMessage());
        }
    }

    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO {
        return $this->pdo;
    }

    public function query(string $sql, bool $single = false): array {
        $query = $this->pdo->query($sql);
        $result = $single ? $query->fetch() : $query->fetchAll();
        return $result !== false ? $result : [];
    }

    public function prepare(string $sql, array $datas = []): PDOStatement {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($datas);
        return $statement;
    }

    public function executeQuery(string $sql, array $datas = [], bool $single = false): array {
        $statement = $this->prepare($sql, $datas);
        $result = $single ? $statement->fetch() : $statement->fetchAll();
        return $result !== false ? $result : [];
    }

    public function executeUpdate(string $sql, array $datas = []): int {
        $statement = $this->prepare($sql, $datas);
        
        if (str_starts_with(strtoupper(trim($sql)), 'INSERT')) {
            return (int) $this->pdo->lastInsertId();
        }
        
        return $statement->rowCount();
    }

    public function beginTransaction(): bool {
        return $this->pdo->beginTransaction();
    }

    public function commit(): bool {
        return $this->pdo->commit();
    }

    public function rollBack(): bool {
        return $this->pdo->rollBack();
    }

    public function inTransaction(): bool {
        return $this->pdo->inTransaction();
    }

    public function lastInsertId(?string $name = null): string|false {
        return $this->pdo->lastInsertId($name);
    }
}