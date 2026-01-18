<?php
namespace App\Core;

class Router {
    private $routes = [];
    private $twig;

    public function __construct($twig) {
        $this->twig = $twig;
    }

    // ... méthodes add, get, post ...

    private function executeHandler($handler) {
        list($controllerName, $method) = explode('@', $handler);
        
        if (class_exists($controllerName)) {
            // On injecte Twig au contrôleur ici
            $controller = new $controllerName($this->twig);
            return $controller->$method();
        }
        die("Contrôleur $controllerName introuvable.");
    }
}