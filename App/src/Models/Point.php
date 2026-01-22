<?php
namespace App\src\Models;

class Point  {
    
    private $id;
    private $user_id;
    private $type;
    private $amount;
    private $description;
    private $balance_after;
    private $createdat;

    // Const
    public function __construct($id, $user_id, $type, $amount = 0, $description = null,$balance_after,$createdat) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->type = $type;
        $this->amount = $amount;
        $this->description = $description;
        $this->balance_after = $balance_after;
        $this->createdat = $createdat ?? date('Y-m-d H:i:s');
    }

    
    public function getId() {
        return $this->id;
    }

    public function getUser_id() {
        return $this->user_id;
    }

    public function getType() {
        return $this->type;
    }    
}