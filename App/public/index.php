<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\src\Core\Router;
use App\src\Core\Database;
use App\src\Controllers\HomeController;
use App\src\Controllers\AuthController;
use App\src\Controllers\ShopController;
use App\src\Controllers\RewardsController;
use App\src\Repositories\ProductRepository;
use App\src\Repositories\UserRepository;
use App\src\Repositories\PointRepository;

session_start();
// 1. Initialisation dyal les dépendances
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../Views');
$twig = new \Twig\Environment($loader, ['cache' => false]);
$pdo = Database::getInstance()->getConnection();

// 2. Configuration dyal l-Router
$router = new Router();

$router->get('/', [HomeController::class, 'index']);

$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);

$router->get('/register', [AuthController::class, 'register']);
$router->post('/register', [AuthController::class, 'register']);

$router->get('/logout', [AuthController::class, 'logout']);

$router->get('/cart', [ShopController::class, 'cart']);

$router->post('/cart', [ShopController::class, 'addToCart']);

$router->post('/cart/remove', [ShopController::class, 'removeFromCart']);
$router->post('/cart/update', [ShopController::class, 'updateCart']);
$router->get('/cart/checkout', [ShopController::class, 'checkout']);
 $router->post('/process_checkout', [ShopController::class, 'processCheckout']);
 $router->get('/purchase-result', [ShopController::class, 'purchaseResult']);

 $router->get('/rewards', [RewardsController::class, 'index']);
 $router->get('/rewards/affordable', [RewardsController::class, 'affordable']);
 $router->get('/rewards/show/{id}', [RewardsController::class, 'show']);
 




// 3. Lancer l-Routing
$productRepo = new ProductRepository($pdo);
$userRepo = new UserRepository($pdo);
$pointRepo = new PointRepository($pdo);


$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

$router->dispatch($requestUri, $requestMethod, [$twig, $pdo]);