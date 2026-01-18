<?php
namespace App\Core; // Assurez-vous que le namespace commence par App\

class Controller {
    protected $twig;

    // On reçoit Twig par injection
    public function __construct($twig) {
        $this->twig = $twig;
    }

    public function render($template, $data = []) {
        echo $this->twig->render($Views, $data);
    }
}