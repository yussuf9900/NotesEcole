<?php

require_once dirname(__DIR__) . '/core/Database.php';

class AnneeScolaireModel {
    private Database $db;
    private PDO $pdo;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->pdo = $this->db->getConnection();
    }

    public function getActive(): ?array {
        $sql = "SELECT * FROM anneescolaires WHERE actif = 1 LIMIT 1";
        $result = $this->db->query($sql, true);
        if (empty($result)) {
            $sqlFallback = "SELECT * FROM anneescolaires ORDER BY id DESC LIMIT 1";
            $result = $this->db->query($sqlFallback, true);
        }
        return !empty($result) ? $result : null;
    }

    public function findAll(): array {
        $sql = "SELECT * FROM anneescolaires ORDER BY id ASC";
        return $this->db->query($sql);
    }

    public function findById(int $id): ?array {
        $sql = "SELECT * FROM anneescolaires WHERE id = :id";
        $result = $this->db->executeQuery($sql, ['id' => $id], true);
        return !empty($result) ? $result : null;
    }
}
