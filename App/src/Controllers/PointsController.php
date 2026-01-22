<?php
class PointsController {
    private $twig;
    private $db;

    public function __construct($twig,$db) {
        $this->twig = $twig;
        $this->db = $db;
    }

    public function showProfile() {
        $userRepository= new PointRepository($this->db);
        $user = ['name' => 'Amine', 'loyalty_points' => 150]; // Masalan mn DB
        
        // 2. Affichi b Twig
        echo $this->twig->render('profile.html.twig', ['user' => $user]);
    }
}