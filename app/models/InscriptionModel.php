<?php

require_once dirname(__DIR__) . '/core/Database.php';

class InscriptionModel {
    private Database $db;
    private PDO $pdo;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->pdo = $this->db->getConnection();
    }

    public function getByAnneeAndClasse(int $anneeId, int $classeId): array {
        $sql = "SELECT i.id AS inscription_id, e.id AS eleve_id, e.nom, e.prenom, e.matricule, i.annee_id, i.classe_id
                FROM inscriptions i
                JOIN eleves e ON e.id = i.eleve_id
                WHERE i.annee_id = :annee_id AND i.classe_id = :classe_id
                ORDER BY e.nom ASC, e.prenom ASC";
        return $this->db->executeQuery($sql, [
            'annee_id' => $anneeId,
            'classe_id' => $classeId
        ]);
    }

    public function findAll(): array {
        $sql = "SELECT * FROM inscriptions ORDER BY id ASC";
        return $this->db->query($sql);
    }

    public function findById(int $id): ?array {
        $sql = "SELECT * FROM inscriptions WHERE id = :id";
        $result = $this->db->executeQuery($sql, ['id' => $id], true);
        return !empty($result) ? $result : null;
    }
}
