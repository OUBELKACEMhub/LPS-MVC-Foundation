<?php
namespace App\src\Core;

class Router {
    private array $routes = [];

    // Bach t-sajjel les routes GET
    public function get(string $path, array $handler): void {
        $this->routes['GET'][$path] = $handler;
    }

    // Bach t-sajjel les routes POST
    public function post(string $path, array $handler): void {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $uri, string $method, array $dependencies): void {
        // Nettoyer l-URI
        $path = parse_url($uri, PHP_URL_PATH);
        $path = str_replace('/Loyalty%20Points%20System/App/public', '', $path);
        $path = rtrim($path, '/') ?: '/';

        if (isset($this->routes[$method][$path])) {
            [$controllerClass, $methodName] = $this->routes[$method][$path];

            $controller = new $controllerClass(...$dependencies);
            $controller->$methodName();
        } else {
            // 404 Error
            http_response_code(404);
            echo $dependencies[0]->render('404.html.twig'); // $dependencies[0] hiya Twig
        }
    }
}