<?php

function find_user_by_email(PDO $pdo, string $email): ?array {
    $sql = "SELECT u.*, r.nomrole 
            FROM utilisateurs u 
            LEFT JOIN roles r ON u.role_id = r.id 
            WHERE u.email = :email";
    $result = executeQuery($pdo, $sql, ['email' => $email], true);
    return !empty($result) ? $result : null;
}

function verify_user_credentials(PDO $pdo, string $email, string $password): ?array {
    $user = find_user_by_email($pdo, trim($email));
    if (!$user) {
        return null;
    }
    
    if ($user['password'] === $password || password_verify($password, $user['password'] ?? '')) {
        return $user;
    }
    
    return null;
}
