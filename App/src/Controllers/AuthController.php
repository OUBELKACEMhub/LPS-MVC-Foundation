<?php
namespace App\src\Controllers;

use App\src\Repositories\UserRepository;

class AuthController {
    
    private $twig;
    private $userRepo;

   public function __construct($twig, $pdo, $productRepo, $userRepo) {
        $this->twig = $twig;
        $this->userRepo = $userRepo; 
    }

   public function login() {
    $error = null; 

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $this->userRepo->findByEmail($email);

        if ($user && password_verify($password, $user->getPasswordHash())) {
            session_start();
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['user_name'] = $user->getName();
            $_SESSION['user_points'] = $user->getTotalPoints();
            $_SESSION['user']=$user;
            header("Location: /Loyalty%20Points%20System/App/public/");
            exit;
        } else {
            $error = "Email ou mot de passe incorrect.";
        }
    }

    echo $this->twig->render('login.html.twig', [
        'error' => $error
    ]);
}
   

    public function register() {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = htmlspecialchars($_POST['name']);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];
 
            $data=[
                'name' => $name,
                'email' => $email,
                'password_hash' => $password
            ];

            if(!$this->userRepo->findByEmail($email)){
                
            }
            $success = $this->userRepo->create($data);
           

           
         

            if ($success) {
                
                header("Location: /Loyalty%20Points%20System/App/public/login"); 
                exit;
            } else {

                $error = "Erreur lors de l'inscription (Email déjà utilisé).";
            }
        }
       
        echo $this->twig->render('register.html.twig', [
            'error' => $error
        ]);
    }

    public function logout() {
        session_destroy();
        header("Location: /Loyalty%20Points%20System/App/public/login"); 
        exit;
    }
}