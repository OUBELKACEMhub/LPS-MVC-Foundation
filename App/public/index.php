<?php
session_start();
use App\src\Controllers\HomeController;
use App\src\Core\Database;
use App\src\Repositories\ProductRepository;
require_once __DIR__ . '/../../vendor/autoload.php';


// 1. Config dial Twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../Views');
$twig = new \Twig\Environment($loader, [
    'cache' => false, 
]);

$request = $_SERVER['REQUEST_URI'];
echo $request ;
$route = str_replace('/Loyalty%20Points%20System/App/public', '', $request);
$route = rtrim($route, '/');

// 3. Wjjeh l-user l-Twig templates
switch ($route) {
    case '':
    case '/':
        $pdo = Database::getInstance()->getConnection();
        $productRepo = new ProductRepository($pdo); 
        $controller = new HomeController($twig, $productRepo); 
        $controller->index();
        break;

    case '/login':
        echo $twig->render('login.html.twig', [
            'title' => 'Contactez-nous'
        ]);
        break;

    case '/cart':
        echo $twig->render('cart.html.twig', [
            'title' => 'cart'
        ]);
        break; 
    case '/dashboard':
        echo $twig->render('userDashboard.html.twig', [
            'title' => 'user Dashboard'
        ]);
        break;       

    default:
        http_response_code(404);
        echo $twig->render('404.html.twig');
        break;
}