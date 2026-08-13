<?php

require_once dirname(__DIR__) . '/core/Database.php';

class UtilisateurModel {
    private Database $db;
    private PDO $pdo;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->pdo = $this->db->getConnection();
    }

    public function findByEmail(string $email): ?array {
        $sql = "SELECT u.*, r.nomrole 
                FROM utilisateurs u 
                LEFT JOIN roles r ON u.role_id = r.id 
                WHERE u.email = :email";
        $result = $this->db->executeQuery($sql, ['email' => trim($email)], true);
        return !empty($result) ? $result : null;
    }

    public function verifyCredentials(string $email, string $password): ?array {
        $user = $this->findByEmail($email);
        if (!$user) {
            return null;
        }

        $userPassword = $user['password'] ?? '';
        if ($userPassword === $password || password_verify($password, $userPassword)) {
            return $user;
        }

        return null;
    }

    public function findAll(): array {
        $sql = "SELECT u.*, r.nomrole 
                FROM utilisateurs u 
                LEFT JOIN roles r ON u.role_id = r.id 
                ORDER BY u.id ASC";
        return $this->db->query($sql);
    }

    public function findById(int $id): ?array {
        $sql = "SELECT u.*, r.nomrole 
                FROM utilisateurs u 
                LEFT JOIN roles r ON u.role_id = r.id 
                WHERE u.id = :id";
        $result = $this->db->executeQuery($sql, ['id' => $id], true);
        return !empty($result) ? $result : null;
    }
}
