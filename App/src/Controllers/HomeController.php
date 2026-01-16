<?php
namespace Controllers;

use Core\Controller;

class HomeController extends Controller
{
    public function index()
    {

        $this->render("home.html.twig",
            [
                "title" => "login",
            ]
        );
    }
}