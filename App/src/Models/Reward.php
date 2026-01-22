<?php
namespace App;

class Reward {
    private $id;
    private $name;
    private $points_required;
    private $descreption;
    private $stock;

    public function __construct($id, $name, $points_required = 0, $descreption = null,$stock) {
        $this->id = $id;
        $this->name = $name;
        $this->points_required = $points_required;
        $this->descreption = $descreption;
        $this->stock = $stock;
    }

    
    public function getId() {
        return $this->id;
    }

    public function getPoints_required() {
        return $this->points_required;
    }

    public function getStock() {
        return $this->stock;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescreption() {
        return $this->descreption;
    }

}