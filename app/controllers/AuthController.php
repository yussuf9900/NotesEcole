<?php

require_once dirname(__DIR__) . '/core/Session.php';
require_once dirname(__DIR__) . '/models/UtilisateurModel.php';

class AuthController {
    private UtilisateurModel $utilisateurModel;

    public function __construct() {
        $this->utilisateurModel = new UtilisateurModel();
    }

    public function login(): void {
        Session::start();

        if (Session::isLoggedIn() && ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET') {
            $this->redirect('/gestion');
        }

        $error = null;

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (!empty($email) && !empty($password)) {
                $user = $this->utilisateurModel->verifyCredentials($email, $password);
                if ($user) {
                    $roleNom = strtolower($user->getNomRole());
                    $isDirectrice = (str_contains($roleNom, 'direction') || $user->getRoleId() === 1 || $user->getEmail() === 'fatouSall@gmail.com');
                    if ($isDirectrice) {
                        Session::set('user', $user);
                        $this->redirect('/gestion');
                    } else {
                        $error = "Accès non autorisé : seul le profil de la directrice est autorisé à se connecter.";
                    }
                } else {
                    $error = "Email ou mot de passe incorrect.";
                }
            } else {
                $error = "Veuillez remplir tous les champs du formulaire.";
            }
        }

        $this->render('PageConnexion.html.php', [
            'error' => $error
        ]);
    }

    public function logout(): void {
        Session::destroy();
        $this->redirect('/login');
    }

    private function render(string $view, array $data = []): void {
        extract($data);
        $viewPath = dirname(__DIR__) . '/views/' . $view;
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("Erreur : La vue '{$view}' est introuvable.");
        }
    }

    private function redirect(string $url): void {
        header("Location: " . $url);
        exit;
    }
}