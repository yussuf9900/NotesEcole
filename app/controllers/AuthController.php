<?php

require_once dirname(__DIR__) . '/models/UtilisateurModel.php';

function login(): void {
    init_session();
    
    if (is_logged_in() && $_SERVER['REQUEST_METHOD'] === 'GET') {
        header('Location: /gestion');
        exit;
    }

    $error = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (!empty($email) && !empty($password)) {
            $pdo = connexionDB();
            $user = verify_user_credentials($pdo, $email, $password);
            if ($user) {
                set_session('user', $user);
                header('Location: /gestion');
                exit;
            } else {
                $error = "Email ou mot de passe incorrect.";
            }
        } else {
            $error = "Veuillez remplir tous les champs du formulaire.";
        }
    }

    require_once dirname(__DIR__) . '/views/PageConnexion.html.php';
}

function logout(): void {
    init_session();
    destroy_session();
    header('Location: /login');
    exit;
}