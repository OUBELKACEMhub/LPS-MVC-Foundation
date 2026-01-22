<?php


namespace App\src\Controllers;
use App\src\Models\RewardModel;
use App\src\Models\PointsModel;

class RewardsController  {
    private $rewardModel;
    private $pointsModel;
     private \Twig\Environment $twig;
    
    public function __construct(\Twig\Environment $twig,$db) {
        $this->twig = $twig;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
        header('Location: /Loyalty%20Points%20System/App/public/login');
        exit;
    }
        
        $this->rewardModel = new RewardModel($db);
        $this->pointsModel = new PointsModel($db);
    }
    
    
    public function index() {

        $rewards = $this->rewardModel->getAll(true);
        
        $this->twig->render('catalog.html.twig', [
            'rewards' => $rewards,
            'user_points' => $_SESSION['total_points'] ?? 0,
            'filter' => 'all'
        ]);


    }
    
    

    
    
    public function show($id) {
        $reward = $this->rewardModel->findById($id);
        
        if (!$reward) {
            $_SESSION['error'] = 'Reward not found';
            $this->redirect('/shopeasy-loyalty/public/rewards');
        }
        
        $this->render('rewards/show', [
            'reward' => $reward,
            'user_points' => $_SESSION['total_points'] ?? 0
        ]);
    }
    
  public function redeem($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/shopeasy-loyalty/public/rewards/show/' . $id);
        }
        
        $userId = $_SESSION['user_id'];
       
        $result = $this->rewardModel->redeem($id, $userId);
        
        if ($result['success']) {
            $_SESSION['total_points'] = $result['new_balance'];
            
            $_SESSION['success'] = sprintf(
                'Successfully redeemed "%s"! %s points deducted. New balance: %s points.',
                $result['reward']['name'],
                number_format($result['points_deducted']),
                number_format($result['new_balance'])
            );
            
            $this->redirect('/shopeasy-loyalty/public/dashboard');
        } else {
          
            $_SESSION['error'] = 'Failed to redeem reward: ' . $result['error'];
            $this->redirect('/shopeasy-loyalty/public/rewards/show/' . $id);
        }
    }




}