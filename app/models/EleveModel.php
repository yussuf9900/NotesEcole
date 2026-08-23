<?php

require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/Entity/Eleve.php';

class EleveModel {
    private Database $db;
    private PDO $pdo;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->pdo = $this->db->getConnection();
    }

    /**
     * @return Eleve[]
     */
    public function findAll(): array {
        $sql = "SELECT * FROM eleves ORDER BY nom ASC, prenom ASC";
        $results = $this->db->query($sql);
        return array_map(fn(array $row) => Eleve::fromArray($row), $results);
    }

    public function findById(int $id): ?Eleve {
        $sql = "SELECT * FROM eleves WHERE id = :id";
        $result = $this->db->executeQuery($sql, ['id' => $id], true);
        return !empty($result) ? Eleve::fromArray($result) : null;
    }

    public function findByMatricule(string $matricule): ?Eleve {
        $sql = "SELECT * FROM eleves WHERE matricule = :matricule";
        $result = $this->db->executeQuery($sql, ['matricule' => $matricule], true);
        return !empty($result) ? Eleve::fromArray($result) : null;
    }
}
