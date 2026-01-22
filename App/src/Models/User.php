<?php
namespace App\src\Models;

class User {
    private $id;
    private $email;
    private $password_hash;
    private $name;
    private $total_points;
    private $created_at;

    public function __construct($id, $email, $password, $name, $total_points = 100, $created_at = null) {
        $this->id = $id;
        $this->email = $email;
        $this->password_hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $this->name = $name;
        $this->total_points = $total_points;
        $this->created_at = $created_at ?? date('Y-m-d H:i:s');
    }




  private function hasherMotDePasse(string $password): string {
    
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

    public function findByEmail(string $email): ?User {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

       return  new User($row['id'], $row['email'], $row['password_hash'], $row['name'], $row['createdat']);
      
    }




     public function findById(int $id): ?User {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return new User($row['id'], $row['email'], $row['password_hash'], $row['name'],$row['total_points'], $row['createdat']);
    }

   

    
    public function getId() {
        return $this->id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPasswordHash() {
        return $this->password_hash;
    }

    public function getName() {
        return $this->name;
    }

    public function getTotalPoints() {
        return $this->total_points;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }


    public function setName($name) {
        $this->name = $name;
    }

    public function setTotalPoints($points) {
        $this->total_points = $points;
    }

   
    public function verifyPassword($password) {
        return password_verify($password, $this->password_hash);
    }

  


}