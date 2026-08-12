<?php

require_once dirname(__DIR__) . '/core/Database.php';

function get_all_matieres(PDO $pdo): array {
    $sql = "SELECT * FROM matieres ORDER BY id ASC";
    return query($pdo, $sql);
}

function get_matieres_by_classe(PDO $pdo, int $classeId): array {
    $sql = "SELECT m.* 
            FROM matieres m
            JOIN matiere_classes mc ON mc.matiere_id = m.id
            WHERE mc.classe_id = :classe_id
            ORDER BY m.id ASC";
    $result = executeQuery($pdo, $sql, ['classe_id' => $classeId]);
    if (empty($result)) {
        return get_all_matieres($pdo);
    }
    return $result;
}
