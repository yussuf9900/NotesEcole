<?php

$Routes = [
    ['/', 'AuthController.php', 'login'],
    ['/login', 'AuthController.php', 'login'],
    ['/logout', 'AuthController.php', 'logout'],
    ['/gestion', 'NoteController.php', 'indexNote'],
    ['/gestion/save', 'NoteController.php', 'enregistrerNote'],
];

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

if ($uri !== '/' && str_ends_with($uri, '/')) {
    $uri = rtrim($uri, '/');
}

$routeFound = false;

foreach ($Routes as $route) {
    if ($route[0] === $uri) {
        $controllerFile = dirname(__DIR__) . '/controllers/' . $route[1];
        $action = $route[2];

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            if (function_exists($action)) {
                $action();
                $routeFound = true;
                break;
            }
        }
    }
}

if (!$routeFound) {
    http_response_code(404);
    echo "<h1>404 - Page non trouvée</h1>";
}
