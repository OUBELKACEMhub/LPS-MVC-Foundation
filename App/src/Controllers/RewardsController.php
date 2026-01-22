<?php

namespace App\src\Controllers;
use App\src\Models\RewardModel;
use App\src\Models\PointModel;

class RewardsController {
    private $rewardModel;
    private $PointdModel;
    private \Twig\Environment $twig;
    private $db;

    public function __construct(\Twig\Environment $twig, $db) {
        $this->twig = $twig;
        $this->db   = $db;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $this->rewardModel = new RewardModel($db);
        $this->PointModel = new PointdModel($db);

    }

 
    public function index() {
        $rewards = $this->rewardModel->getAll(true);

        echo $this->twig->render('catalog.html.twig', [
            'rewards'     => $rewards,
            'user_points' => $_SESSION['user_points'] ?? 0,
            'filter'      => 'all'
        ]);
    }

    
   
    public function affordable() {
        $userPoints = $_SESSION['user_points'] ?? 0;
        $rewards    = $this->rewardModel->getAffordableRewards($userPoints);

        echo $this->twig->render('catalog.html.twig', [
            'rewards'     => $rewards,
            'user_points' => $userPoints,
            'filter'      => 'affordable'
        ]);
    }

  
    public function show($id) {
        $reward = $this->rewardModel->findById($id);

        if (!$reward) {
            // Reward introuvable → retour au catalogue
            header('Location: /Loyalty%20Points%20System/App/public/rewards');
            exit;
        }

        echo $this->twig->render('show.html.twig', [
            'reward'      => $reward,
            'user_points' => $_SESSION['user_points'] ?? 0
        ]);
    }


    public function redeem($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Loyalty%20Points%20System/App/public/rewards');
            exit;
        }

        $confirmation = $_POST['confirmation'] ?? '';

        if ($confirmation !== 'on') {
            header('Location: /Loyalty%20Points%20System/App/public/rewards');
            exit;
        }

      
        $result = $this->rewardModel->processRedemption($id, $_SESSION['user_id']);

        if ($result['success']) {
           
            $stmtUser = $this->db->prepare("SELECT total_points FROM users WHERE id = ?");
            $stmtUser->execute([$_SESSION['user_id']]);
            $userRow  = $stmtUser->fetch(\PDO::FETCH_ASSOC);
            if ($userRow) {
                $_SESSION['user_points'] = $userRow['total_points'];
            }

            // Stocke les données du reward rédempt en session pour la page de succès
            $_SESSION['last_redeemed_reward'] = $result['reward'];
            $_SESSION['success'] = "Récompense \"" . $result['reward']['name'] . "\" échangée avec succès !";

            header('Location: /Loyalty%20Points%20System/App/public/rewards/success');
            exit;
        }

        
        $_SESSION['error'] = $result['error'] ?? 'La rédemption a échoué.';
        header('Location: /Loyalty%20Points%20System/App/public/rewards/show/' . $id);
        exit;
    }

    
    public function success() {
        $reward = $_SESSION['last_redeemed_reward'] ?? null;
        $message = $_SESSION['success'] ?? 'Récompense échangée !';

        // Nettoie la session après lecture
        unset($_SESSION['last_redeemed_reward']);
        unset($_SESSION['success']);

        if (!$reward) {
            header('Location: /Loyalty%20Points%20System/App/public/rewards');
            exit;
        }

        echo $this->twig->render('success.html.twig', [
            'reward'      => $reward,
            'message'     => $message,
            'user_points' => $_SESSION['user_points'] ?? 0
        ]);
    }
}
