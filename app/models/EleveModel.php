<?php

require_once dirname(__DIR__) . '/core/Database.php';

class EleveModel {
    private Database $db;
    private PDO $pdo;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->pdo = $this->db->getConnection();
    }

    public function findAll(): array {
        $sql = "SELECT * FROM eleves ORDER BY nom ASC, prenom ASC";
        return $this->db->query($sql);
    }

    public function findById(int $id): ?array {
        $sql = "SELECT * FROM eleves WHERE id = :id";
        $result = $this->db->executeQuery($sql, ['id' => $id], true);
        return !empty($result) ? $result : null;
    }

    public function findByMatricule(string $matricule): ?array {
        $sql = "SELECT * FROM eleves WHERE matricule = :matricule";
        $result = $this->db->executeQuery($sql, ['matricule' => $matricule], true);
        return !empty($result) ? $result : null;
    }
}
