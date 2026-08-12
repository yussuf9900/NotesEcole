<?php

require_once dirname(__DIR__) . '/core/Database.php';

function get_all_classes(PDO $pdo): array {
    $sql = "SELECT * FROM classes ORDER BY id ASC";
    return query($pdo, $sql);
}

function get_classe_by_id(PDO $pdo, int $id): ?array {
    $sql = "SELECT * FROM classes WHERE id = :id";
    $result = executeQuery($pdo, $sql, ['id' => $id], true);
    return !empty($result) ? $result : null;
}
