<?php
// 1. Charger l'autoloader de Composer
require_once __DIR__ . '/../../vendor/autoload.php';

// 2. Configurer où se trouvent tes fichiers HTML
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig = new \Twig\Environment($loader);

// 3. Essayer d'afficher une page
try {
    echo $twig->render('test.html.twig', [
        'message' => 'Félicitations ! Twig fonctionne correctement.'
    ]);
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}