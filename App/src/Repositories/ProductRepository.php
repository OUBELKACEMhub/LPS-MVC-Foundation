<?php
namespace App\src\Repositories;

use App\src\Models\Product; 
use PDO;

class ProductRepository   {
    private $db;
   
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    
    public function findAll(): array {
        $stmt = $this->db->query("SELECT * FROM products");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $products = [];
        foreach ($rows as $row) {
            $products[] = new Product(
                $row['id'],
                $row['name'],
                $row['description'],
                $row['price'],
                $row['icon']
            );
        }
        return $products;
    }

 
    public function findById(int $id): ?Product {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return new Product($row['id'], $row['name'], $row['description'], $row['price'], $row['icon']);
    }

    public function save(Product $product): bool {
        $sql = "INSERT INTO products (name, description, price, icon) 
                VALUES (:name, :description, :price, :icon)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'name'        => $product->getName(),
            'description' => $product->getDescription(),
            'price'       => $product->getPrice(),
            'icon'        => $product->getIcon()
        ]);
    }
}