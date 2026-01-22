<?php
namespace App\src\Repositories;
use App\src\Models\User; 
use PDO;

class UserRepository   {
    private $db;
    
   
    public function __construct(PDO $db) {
        $this->db = $db;
        
    }

    
    public function findAll(): array {
        $stmt = $this->db->query("SELECT * FROM users");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $products = [];
        foreach ($rows as $row) {
            $users[] = new User(
                $row['id'],
                $row['email'],
                $row['password_hash'],
                $row['name'],
                $row['total_points'],
                $row['createdat']
            );
        }
        return $users;
    }

  
    public function findById(int $id): ?User {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return new User($row['id'], $row['email'], $row['password_hash'], $row['name'],$row['total_points'], $row['createdat']);
    }

   

    public function findByEmail(string $email): ?User {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

       return  new User($row['id'], $row['email'], $row['password_hash'], $row['name'],$row['total_points'], $row['createdat']);
      
    }
    

    public function create(array $data): bool {
    try {
        $sql = "INSERT INTO users (name, email, password_hash, total_points, createdat) 
                VALUES (:name, :email, :password_hash, 0, NOW())"; 
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':password_hash' => $data['password_hash']
        ]);
    } catch (\PDOException $e) {
        return false;
    }
}

public function updatePoints($id,$newtotalPoint){
$sql= "UPDATE users SET total_points=:total_points WHERE id=:id";
$stmt = $this->db->prepare($sql);  
return $stmt->execute([
            ':total_points' => $newtotalPoint,
            ':id' => $id
        ]);
}

}