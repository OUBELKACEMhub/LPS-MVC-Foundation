<?php
namespace App\src\Controllers;

use App\src\Repositories\ProductRepository; // T-akked mn l-namespace shih


class HomeController {
    private $twig;
    private $productRepo;

   public function __construct($twig, $pdo) {
    $this->twig = $twig;
    $this->productRepo = new \App\src\Repositories\ProductRepository($pdo);
}

    public function index() {
        // 2. Jib l-data mn l-Repository
        $allProducts = $this->productRepo->findAll();
       
        $data = [
            "name" => $_SESSION['user_name'],
            "points" => $_SESSION['user_points'],
            "title" => "Accueil",
            "products" => $allProducts 
        ];

        // 3. Affichage
        echo $this->twig->render("index.html.twig", $data);
    }
}