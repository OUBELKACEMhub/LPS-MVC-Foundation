<?php

use Core\Router;

$router = new Router();

$router->get("/", "Controllers\\HomeController@index");
$router->add('GET', '/login', 'AuthController@login');
$router->add('POST', '/login', 'AuthController@login');
$router->add('POST', '/register', 'AuthController@register');
$router->add('GET', '/logout', 'AuthController@logout');


$router->add('GET', '/dashboard', 'DashboardController@index');
$router->add('GET', '/profile', 'DashboardController@profile');


$router->add('GET', '/points/history', 'PointsController@history');
$router->add('POST', '/points/add', 'PointsController@add');
$router->add('GET', '/points/expiring', 'PointsController@expiring');


$router->add('GET', '/rewards', 'RewardsController@index');
$router->add('GET', '/rewards/{id}', 'RewardsController@show');
$router->add('POST', '/rewards/{id}/redeem', 'RewardsController@redeem');


$router->generate_path();
// $router->add('GET', '/dashboard', 'ShopController@index');
// $router->add('GET', '/profile', 'ShopController@profile');4

?>