<?php
// App/public/index.php

require_once __DIR__ . '/../../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');

$twig = new \Twig\Environment($loader, [
    'cache' => false, 
    'debug' => true
]);

// 3. Données de test
$fakeUser = [
    'name' => 'Ahmed',
    'points' => 450,
    'is_logged' => true
];

try {
    echo $twig->render('test.html.twig', [
        'nom' => 'Ahmed',
        'user' => $fakeUser 
    ]);
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}