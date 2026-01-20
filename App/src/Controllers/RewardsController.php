<?php
namespace App\src\Controllers;

use App\src\Repositories\PointRepository;

class RewardsController {
    
    private $twig;
    private $PointRepo;
   public function __construct($twig, $pdo) {
        $this->twig = $twig;
        $this->PointRepo=new PointRepository($db);
    }

  
    public function index() {
      
        if (!isset($_SESSION['user_id'])) {
            header('Location: /Loyalty%20Points%20System/App/public/login');
            exit;
        }

       
        $rewards = $this->PointRepo->findAll();
        echo $this->twig->render('catalog.html.twig', [
            'rewards' => $rewards,
            'user_points' => $_SESSION['user_points'] ?? 0,
            'user_name' => $_SESSION['user_name'] ?? 'Invité'
        ]);
    }

}