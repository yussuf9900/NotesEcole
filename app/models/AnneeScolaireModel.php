<?php

require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/Entity/AnneeScolaire.php';

class AnneeScolaireModel {
    private Database $db;
    private PDO $pdo;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->pdo = $this->db->getConnection();
    }

    public function getActive(): ?AnneeScolaire {
        $sql = "SELECT * FROM anneescolaires WHERE actif = 1 LIMIT 1";
        $result = $this->db->query($sql, true);
        if (empty($result)) {
            $sqlFallback = "SELECT * FROM anneescolaires ORDER BY id DESC LIMIT 1";
            $result = $this->db->query($sqlFallback, true);
        }
        return !empty($result) ? AnneeScolaire::fromArray($result) : null;
    }

    /**
     * @return AnneeScolaire[]
     */
    public function findAll(): array {
        $sql = "SELECT * FROM anneescolaires ORDER BY id ASC";
        $results = $this->db->query($sql);
        return array_map(fn(array $row) => AnneeScolaire::fromArray($row), $results);
    }

    public function findById(int $id): ?AnneeScolaire {
        $sql = "SELECT * FROM anneescolaires WHERE id = :id";
        $result = $this->db->executeQuery($sql, ['id' => $id], true);
        return !empty($result) ? AnneeScolaire::fromArray($result) : null;
    }
}
