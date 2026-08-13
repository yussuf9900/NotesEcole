<?php

// Inclusion du noyau
require_once dirname(__DIR__) . '/app/core/Database.php';
require_once dirname(__DIR__) . '/app/core/Session.php';
require_once dirname(__DIR__) . '/app/core/Router.php';

// Inclusion des 10 modèles
require_once dirname(__DIR__) . '/app/models/AnneeScolaireModel.php';
require_once dirname(__DIR__) . '/app/models/ClasseModel.php';
require_once dirname(__DIR__) . '/app/models/EleveModel.php';
require_once dirname(__DIR__) . '/app/models/InscriptionModel.php';
require_once dirname(__DIR__) . '/app/models/RoleModel.php';
require_once dirname(__DIR__) . '/app/models/UtilisateurModel.php';
require_once dirname(__DIR__) . '/app/models/MatiereModel.php';
require_once dirname(__DIR__) . '/app/models/MatiereClasseModel.php';
require_once dirname(__DIR__) . '/app/models/PeriodeModel.php';
require_once dirname(__DIR__) . '/app/models/EvaluationModel.php';

// Inclusion des contrôleurs
require_once dirname(__DIR__) . '/app/controllers/AuthController.php';
require_once dirname(__DIR__) . '/app/controllers/NoteController.php';

// Démarrage de la session
Session::start();

// Configuration et exécution du routeur
$router = new Router();

$router->get('/', 'AuthController', 'login');
$router->get('/login', 'AuthController', 'login');
$router->post('/login', 'AuthController', 'login');
$router->get('/logout', 'AuthController', 'logout');
$router->get('/gestion', 'NoteController', 'index');
$router->post('/gestion/save', 'NoteController', 'save');

$router->dispatch();