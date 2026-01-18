<?php
namespace App\src\Models\Repositories;

use App\src\Models\Point;
use PDO;

class PointRepository  {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    
    public function findByUserId(int $userId): array {
        $stmt = $this->db->prepare("SELECT * FROM points_transactions WHERE user_id = :user_id ORDER BY createdat DESC");
        $stmt->execute(['user_id' => $userId]);
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $points = [];

        foreach ($results as $row) {
            $points[] = new Point(
                $row['id'],
                $row['user_id'],
                $row['type'],
                $row['amount'],
                $row['description'],
                $row['balance_after'],
                $row['createdat']
            );
        }
        return $points;
    }

    // Create: Sweb point jdid
    public function save(Point $point): bool {
        $sql = "INSERT INTO points (user_id, type, amount, description, balance_after, createdat) 
                VALUES (:user_id, :type, :amount, :description, :balance_after, :createdat)";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            'user_id'       => $point->getUser_id(),
            'type'          => $point->getType(),
            'amount'        => $point->getAmount(), // Khssk t-zid getter f l-Class Point
            'description'   => $point->getDescription(), // Khssk t-zid getter
            'balance_after' => $point->getBalance_after(), // Khssk t-zid getter
            'createdat'     => $point->getCreatedat() // Khssk t-zid getter
        ]);
    }

    public function findById(int $id): ?Point {
        $stmt = $this->db->prepare("SELECT * FROM points WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return new Point($row['id'], $row['user_id'], $row['type'], $row['amount'], $row['description'], $row['balance_after'], $row['createdat']);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM points WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}