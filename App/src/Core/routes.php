<?php
namespace App\Core;
use App\Core\Router;

// On assume que $router est passé depuis index.php
$router=new Router();

$router->get("/", "App\Controllers\HomeController@index");
$router->get("/login", "App\Controllers\AuthController@login");

$router->post("/login", "App\Controllers\AuthController@login");
$router->get("/register", "App\Controllers\AuthController@register");
$router->post("/register", "App\Controllers\AuthController@register");
$router->get("/logout", "App\Controllers\AuthController@logout");

$router->get("/dashboard", "App\Controllers\DashboardController@index");

// Exemple Shop (puisque tu as ShopController)
$router->get("/shop", "App\Controllers\ShopController@index");
$router->get("/shop/cart", "App\Controllers\ShopController@cart");