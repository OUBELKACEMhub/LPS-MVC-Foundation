<?php
require_once __DIR__ . '/../../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig = new \Twig\Environment($loader, [
    'cache' => false, 
    'debug' => true
]);

// 1. On définit l'utilisateur (Tableau associatif)
$fakeUser = [
    'name' => 'Ahmed',     // C'est cette valeur qu'on veut afficher
    'points' => 450,
    'is_logged' => true
];

try {
    // 2. On l'envoie à la vue sous le nom 'user'
    echo $twig->render('test.html.twig', [
        'message' => 'Test d\'affichage',
        'user' => $fakeUser 
    ]);
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}

try {
    // 2. On l'envoie à la vue sous le nom 'user'
    echo $twig->render('base.html.twig', [
        'message' => 'Test d\'affichage',
        'user' => $fakeUser 
    ]);
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}