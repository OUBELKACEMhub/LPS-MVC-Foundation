<?php

require_once __DIR__ . '/../vendor/autoload.php';


$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');

$twig = new \Twig\Environment($loader, [
    // Optionnel : Désactiver le cache pour le développement pour voir les changements immédiatement
    'cache' => false, 
    'debug' => true,
]);

// 3. Essayer d'afficher une page
try {
    echo $twig->render('test.html.twig', [
        'message' => 'Félicitations1 ! Twig fonctionne correctement1.',
        'message2' => 'Félicitations2 ! Twig fonctionne correctement2.'
    ]);
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}