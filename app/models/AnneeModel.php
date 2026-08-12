<?php

require_once dirname(__DIR__) . '/core/Database.php';

function get_annee_active(PDO $pdo): ?array {
    $sql = "SELECT * FROM anneescolaires WHERE actif = 1 LIMIT 1";
    $result = query($pdo, $sql, true);
    if (empty($result)) {
        $sqlFallback = "SELECT * FROM anneescolaires ORDER BY id DESC LIMIT 1";
        $result = query($pdo, $sqlFallback, true);
    }
    return !empty($result) ? $result : null;
}

function get_all_annees(PDO $pdo): array {
    $sql = "SELECT * FROM anneescolaires ORDER BY id ASC";
    return query($pdo, $sql);
}
