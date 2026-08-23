<?php

require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/Entity/Matiere.php';
require_once dirname(__DIR__) . '/Entity/MatiereClasse.php';
require_once __DIR__ . '/MatiereModel.php';

class MatiereClasseModel {
    private Database $db;
    private PDO $pdo;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->pdo = $this->db->getConnection();
    }

    /**
     * @return Matiere[]
     */
    public function getMatieresByClasseId(int $classeId): array {
        $sql = "SELECT m.* 
                FROM matieres m
                JOIN matiere_classes mc ON mc.matiere_id = m.id
                WHERE mc.classe_id = :classe_id
                ORDER BY m.id ASC";
        $results = $this->db->executeQuery($sql, ['classe_id' => $classeId]);
        if (empty($results)) {
            $matiereModel = new MatiereModel();
            return $matiereModel->findAll();
        }
        return array_map(fn(array $row) => Matiere::fromArray($row), $results);
    }

    /**
     * @return MatiereClasse[]
     */
    public function findAll(): array {
        $sql = "SELECT * FROM matiere_classes ORDER BY id ASC";
        $results = $this->db->query($sql);
        return array_map(fn(array $row) => MatiereClasse::fromArray($row), $results);
    }

    public function findById(int $id): ?MatiereClasse {
        $sql = "SELECT * FROM matiere_classes WHERE id = :id";
        $result = $this->db->executeQuery($sql, ['id' => $id], true);
        return !empty($result) ? MatiereClasse::fromArray($result) : null;
    }
}
