<?php

require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/Entity/Inscription.php';

class InscriptionModel {
    private Database $db;
    private PDO $pdo;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->pdo = $this->db->getConnection();
    }

    /**
     * @return Inscription[]
     */
    public function getByAnneeAndClasse(int $anneeId, int $classeId): array {
        $sql = "SELECT i.id AS inscription_id, e.id AS eleve_id, e.nom, e.prenom, e.matricule, i.annee_id, i.classe_id
                FROM inscriptions i
                JOIN eleves e ON e.id = i.eleve_id
                WHERE i.annee_id = :annee_id AND i.classe_id = :classe_id
                ORDER BY e.nom ASC, e.prenom ASC";
        $results = $this->db->executeQuery($sql, [
            'annee_id' => $anneeId,
            'classe_id' => $classeId
        ]);
        return array_map(fn(array $row) => Inscription::fromArray($row), $results);
    }

    /**
     * @return Inscription[]
     */
    public function findAll(): array {
        $sql = "SELECT * FROM inscriptions ORDER BY id ASC";
        $results = $this->db->query($sql);
        return array_map(fn(array $row) => Inscription::fromArray($row), $results);
    }

    public function findById(int $id): ?Inscription {
        $sql = "SELECT * FROM inscriptions WHERE id = :id";
        $result = $this->db->executeQuery($sql, ['id' => $id], true);
        return !empty($result) ? Inscription::fromArray($result) : null;
    }
}
