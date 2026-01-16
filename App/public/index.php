<?php

require_once __DIR__ . '/../vendor/autoload.php';

// use App\Core\Router;
// use App\Core\RouterException;
// use App\Controllers\AuthController;
// use App\Controllers\DashboardController;

// session_start();

// $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../views'); 
// $twig = new \Twig\Environment($loader, [
//     'cache' => false, 
//     'debug' => true
// ]);

// $authController = new AuthController($twig);
// $dashboardController = new DashboardController($twig);

// $router = new Router($_GET['url'] ?? '/');

// // --- DÉFINITION DES ROUTES ---

// $router->get('/', [$authController, 'showLogin']);

// $router->post('/login', [$authController, 'login']);

// $router->get('/logout', [$authController, 'logout']);

// $router->get('/dashboard', [$dashboardController, 'index']);


// try {
//     $router->run();
// } catch (RouterException $e) {
//     header("HTTP/1.0 404 Not Found");
//     echo $twig->render('404.html.twig', ['error' => $e->getMessage()]);
// } catch (Exception $e) {
//     echo "Erreur système : " . $e->getMessage();
// }

echo "hello";