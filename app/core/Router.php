<?php

class Router {
    private array $routes = [];

    public function get(string $path, string $controller, string $action): void {
        $this->routes['GET'][$path] = [
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function post(string $path, string $controller, string $action): void {
        $this->routes['POST'][$path] = [
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function dispatch(): void {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        if ($uri !== '/' && str_ends_with($uri, '/')) {
            $uri = rtrim($uri, '/');
        }

        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if (isset($this->routes[$method][$uri])) {
            $target = $this->routes[$method][$uri];
            $controllerClass = $target['controller'];
            $action = $target['action'];

            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();
                if (method_exists($controller, $action)) {
                    $controller->$action();
                    return;
                }
            }
        }

        http_response_code(404);
        echo "<!DOCTYPE html>
        <html lang='fr'>
        <head>
            <meta charset='UTF-8'>
            <title>404 - Page non trouvée</title>
            <style>
                body { font-family: sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; background: #F7F7F4; color: #151915; }
                .card { text-align: center; background: white; padding: 40px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
                h1 { margin-bottom: 10px; color: #20503A; }
                a { color: #2E6B4E; text-decoration: none; font-weight: bold; }
            </style>
        </head>
        <body>
            <div class='card'>
                <h1>404 - Page non trouvée</h1>
                <p>La page que vous recherchez n'existe pas.</p>
                <p><a href='/'>Retour à l'accueil</a></p>
            </div>
        </body>
        </html>";
    }
}
