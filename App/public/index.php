<?php

require_once __DIR__ . '/../../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates/layouts');

$twig = new \Twig\Environment($loader, [
    // Optionnel : Désactiver le cache pour le développement pour voir les changements immédiatement
    'cache' => false, 
    'debug' => true,
]);



$fakeUser = [
    'name' => 'Ahmed',
    'points' => 450,
    'is_logged' => true
];

try {
    // On passe le tableau 'user' à la vue
    echo $twig->render('test.html.twig', [
        'message' => 'Test réussi avec succès !',
        'user' => $fakeUser  // <--- C'est ici que la magie opère
    ]);
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}

