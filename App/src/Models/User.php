<?php
namespace App;

class User {
    private $id;
    private $email;
    private $password_hash;
    private $name;
    private $total_points;
    private $created_at;

    // Constructeur
    public function __construct($id, $email, $password, $name, $total_points = 0, $created_at = null) {
        $this->id = $id;
        $this->email = $email;
        $this->password_hash = hasherMotDePasse($password);
        $this->name = $name;
        $this->total_points = $total_points;
        $this->created_at = $created_at ?? date('Y-m-d H:i:s');
    }






    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data) {
        $sql = "INSERT INTO users (name, email, password_hash, total_points, created_at) 
                VALUES (:name, :email, :password_hash, 0, NOW())"; 
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':password_hash' => password_hash($data['password'], PASSWORD_BCRYPT) 
        ]);
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

    function hasherMotDePasse($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}
}


