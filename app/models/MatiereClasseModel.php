<?php

require_once dirname(__DIR__) . '/core/Database.php';
require_once __DIR__ . '/MatiereModel.php';

class MatiereClasseModel {
    private Database $db;
    private PDO $pdo;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->pdo = $this->db->getConnection();
    }

    public function getMatieresByClasseId(int $classeId): array {
        $sql = "SELECT m.* 
                FROM matieres m
                JOIN matiere_classes mc ON mc.matiere_id = m.id
                WHERE mc.classe_id = :classe_id
                ORDER BY m.id ASC";
        $result = $this->db->executeQuery($sql, ['classe_id' => $classeId]);
        if (empty($result)) {
            $matiereModel = new MatiereModel();
            return $matiereModel->findAll();
        }
        return $result;
    }

    public function findAll(): array {
        $sql = "SELECT * FROM matiere_classes ORDER BY id ASC";
        return $this->db->query($sql);
    }

    public function findById(int $id): ?array {
        $sql = "SELECT * FROM matiere_classes WHERE id = :id";
        $result = $this->db->executeQuery($sql, ['id' => $id], true);
        return !empty($result) ? $result : null;
    }
}
