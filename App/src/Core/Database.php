<?php
declare(strict_types=1);

namespace App\src\Core;

use PDO;
use Exception; 
class Database {
    private static ?Database $instance = null;
    private PDO $pdo;

    private string $host = 'localhost';
    private string $db   = 'loyaltypointssystem';
    private string $user = 'root';
    private string $pass = '';
    private string $charset = 'utf8mb4';

    private function __construct() {
        $dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
       
        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

   public static function getInstance(): Database {
    if (self::$instance === null) {
        self::$instance = new self();
    }
    return self::$instance;
}

    public function getConnection(): PDO {
        return $this->pdo;
    }
}

$db = Database::getInstance();
$pdo = $db->getConnection();

