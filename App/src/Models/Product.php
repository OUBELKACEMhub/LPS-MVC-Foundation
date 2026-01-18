<?php

namespace App\src\Models;

class Product {
    private $id;
    private $name;
    private $description;
    private $price;
    private $icon;

    // Constructeur bach t-initialiser l-data
    public function __construct($id, $name, $description, $price, $icon) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->icon = $icon;
    }

    // Getters: Twig kiy-hder m3ahom nichan bach i-jib l-data
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getIcon() {
        return $this->icon;
    }
}