<?php
namespace App\Controllers;

use App\Models\User;

class AuthController {
    
    private $twig;

    public function __construct($twig) {
        $this->twig = $twig;
    }

    // Login Action
    public function login() {
        $error = null; 

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $email = $_POST['email'];
            $password = $_POST['password'];

            $userModel = new User();
            $user = $userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password_hash'])) {

            $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_points'] = $user['total_points'];
                
                header("Location: /dashboard");
                exit;
            } else {
                $error = "Email ou mot de passe incorrect.";
            }
        }

        echo $this->twig->render('login.html.twig', [
            'error' => $error
        ]);
    }

    // Register Action


    public function register() {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $data = [
                'name' => htmlspecialchars($_POST['name']),
                'email' => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
                'password' => $_POST['password'] 
            ];

            $userModel = new User();
         
            if ($userModel->create($data)) {
                header("Location: /login"); 
                exit;
            } else {
                $error = "Erreur lors de l'inscription (Email peut-être déjà utilisé).";
                header("Location: /home"); 
            }
        }
       ²                                                                                                                                                                                                                                                   
        echo $this->twig->render('register.html.twig', [
            'error' => $error
        ]);
    }

    public function logout() {
        session_destroy();
        header("Location: /"); 
        exit;
    }
}