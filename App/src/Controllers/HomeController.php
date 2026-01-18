<?php
namespace App\src\Controllers;

use App\src\Repositories\ProductRepository; // T-akked mn l-namespace shih

class HomeController {
    private $twig;
    private $productRepo;

    // 1. Injecter Twig u l-Repository f l-constructeur (Best Practice)
    public function __construct($twig, ProductRepository $productRepo) {
        $this->twig = $twig;
        $this->productRepo = $productRepo;
    }

    public function index() {
        // 2. Jib l-data mn l-Repository
        $allProducts = $this->productRepo->findAll();
       
        $data = [
            "name" => "Ahmed",
            "points" => 100,
            "title" => "Accueil",
            "products" => $allProducts // Islah l-syntax: khdem b string key "products"
        ];

        // 3. Affichage
        echo $this->twig->render("index.html.twig", $data);
    }
}