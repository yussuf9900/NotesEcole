<?php

require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/Entity/Classe.php';

class ClasseModel {
    private Database $db;
    private PDO $pdo;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->pdo = $this->db->getConnection();
    }

    public function findAll(): array {
        $sql = "SELECT * FROM classes ORDER BY id ASC";
        $results = $this->db->query($sql);
        return array_map(fn(array $row) => Classe::fromArray($row), $results);
    }

    public function findById(int $id): ?Classe {
        $sql = "SELECT * FROM classes WHERE id = :id";
        $result = $this->db->executeQuery($sql, ['id' => $id], true);
        return !empty($result) ? Classe::fromArray($result) : null;
    }
}
