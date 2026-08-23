<?php

require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/Entity/Periode.php';

class PeriodeModel {
    private Database $db;
    private PDO $pdo;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->pdo = $this->db->getConnection();
    }

    /**
     * @return Periode[]
     */
    public function findAll(): array {
        $sql = "SELECT * FROM periodes ORDER BY id ASC";
        $results = $this->db->query($sql);
        return array_map(fn(array $row) => Periode::fromArray($row), $results);
    }

    public function findById(int $id): ?Periode {
        $sql = "SELECT * FROM periodes WHERE id = :id";
        $result = $this->db->executeQuery($sql, ['id' => $id], true);
        return !empty($result) ? Periode::fromArray($result) : null;
    }
}
