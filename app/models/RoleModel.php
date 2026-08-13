<?php

require_once dirname(__DIR__) . '/core/Database.php';

class RoleModel {
    private Database $db;
    private PDO $pdo;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->pdo = $this->db->getConnection();
    }

    public function findAll(): array {
        $sql = "SELECT * FROM roles ORDER BY id ASC";
        return $this->db->query($sql);
    }

    public function findById(int $id): ?array {
        $sql = "SELECT * FROM roles WHERE id = :id";
        $result = $this->db->executeQuery($sql, ['id' => $id], true);
        return !empty($result) ? $result : null;
    }
}
