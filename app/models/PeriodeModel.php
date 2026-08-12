<?php

function get_all_periodes(PDO $pdo): array {
    $sql = "SELECT * FROM periodes ORDER BY id ASC";
    return query($pdo, $sql);
}
