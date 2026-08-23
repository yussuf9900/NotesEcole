<?php

require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/Entity/Role.php';

class RoleModel {
    private Database $db;
    private PDO $pdo;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->pdo = $this->db->getConnection();
    }

    /**
     * @return Role[]
     */
    public function findAll(): array {
        $sql = "SELECT * FROM roles ORDER BY id ASC";
        $results = $this->db->query($sql);
        return array_map(fn(array $row) => Role::fromArray($row), $results);
    }

    public function findById(int $id): ?Role {
        $sql = "SELECT * FROM roles WHERE id = :id";
        $result = $this->db->executeQuery($sql, ['id' => $id], true);
        return !empty($result) ? Role::fromArray($result) : null;
    }
}
