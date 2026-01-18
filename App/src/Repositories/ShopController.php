<?php
class ShopController {
    public function index($twig, $db) {
        $model = new Product($db);
        
        $products = $model->getAllProducts();
        
        echo $twig->render('shop.html.twig', ['products' => $products]);
    }

    public function store($twig, $db) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new Product($db);
            // Controller kiy-tleb mn l-Model i-dir (CREATE)
            $model->addProduct($_POST['name'], $_POST['price']);
            
            // Redirect mor l-creation
            header('Location: /shop');
        }
    }
}