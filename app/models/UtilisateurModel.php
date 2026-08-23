<?php

require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/Entity/Utilisateur.php';

class UtilisateurModel {
    private Database $db;
    private PDO $pdo;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->pdo = $this->db->getConnection();
    }

    public function findByEmail(string $email): ?Utilisateur {
        $sql = "SELECT u.*, r.nomrole 
                FROM utilisateurs u 
                LEFT JOIN roles r ON u.role_id = r.id 
                WHERE u.email = :email";
        $result = $this->db->executeQuery($sql, ['email' => trim($email)], true);
        return !empty($result) ? Utilisateur::fromArray($result) : null;
    }

    public function verifyCredentials(string $email, string $password): ?Utilisateur {
        $user = $this->findByEmail($email);
        if (!$user) {
            return null;
        }

        $userPassword = $user->getPassword() ?? '';
        if ($userPassword === $password || password_verify($password, $userPassword)) {
            return $user;
        }

        return null;
    }

    /**
     * @return Utilisateur[]
     */
    public function findAll(): array {
        $sql = "SELECT u.*, r.nomrole 
                FROM utilisateurs u 
                LEFT JOIN roles r ON u.role_id = r.id 
                ORDER BY u.id ASC";
        $results = $this->db->query($sql);
        return array_map(fn(array $row) => Utilisateur::fromArray($row), $results);
    }

    public function findById(int $id): ?Utilisateur {
        $sql = "SELECT u.*, r.nomrole 
                FROM utilisateurs u 
                LEFT JOIN roles r ON u.role_id = r.id 
                WHERE u.id = :id";
        $result = $this->db->executeQuery($sql, ['id' => $id], true);
        return !empty($result) ? Utilisateur::fromArray($result) : null;
    }
}
