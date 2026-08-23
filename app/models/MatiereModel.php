<?php

require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/Entity/Matiere.php';

class MatiereModel {
    private Database $db;
    private PDO $pdo;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->pdo = $this->db->getConnection();
    }

    /**
     * @return Matiere[]
     */
    public function findAll(): array {
        $sql = "SELECT * FROM matieres ORDER BY id ASC";
        $results = $this->db->query($sql);
        return array_map(fn(array $row) => Matiere::fromArray($row), $results);
    }

    public function findById(int $id): ?Matiere {
        $sql = "SELECT * FROM matieres WHERE id = :id";
        $result = $this->db->executeQuery($sql, ['id' => $id], true);
        return !empty($result) ? Matiere::fromArray($result) : null;
    }
}
