<?php
class PointsController {
    private $twig;

    public function __construct($twig) {
        $this->twig = $twig;
    }

    public function showProfile() {
        $userRepository= new PointRepository();
        $user = ['name' => 'Amine', 'loyalty_points' => 150]; // Masalan mn DB
        
        // 2. Affichi b Twig
        echo $this->twig->render('profile.html.twig', ['user' => $user]);
    }
}