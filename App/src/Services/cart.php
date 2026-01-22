<?php
namespace App\src\Services;

class Cart
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

   public function addItem(int $id, string $name, float $price, int $quantity): void 
{
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$id] = [
            'id'=>$id,
            'name'     => $name,
            'price'    => $price,
            'quantity' => $quantity
        ];
    }
}

    
    public function getItems(): array
    {
        return $_SESSION['cart'] ?? [];
    }

    public function getCount(): int
    {
        $count = 0;
        foreach ($this->getItems() as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }


    public function  getTotal(): float {
        $total = 0;
        foreach ($this->getItems() as $item) {
            $total += $item['price']*$item['quantity'];
        }
        return $total;
    }



    public function removeItem($productId){
        if (isset($_SESSION['cart'])) {

            if (array_key_exists($productId, $_SESSION['cart'])) {
                unset($_SESSION['cart'][$productId]);
            }
       }       
        }


    public  function isEmpty():bool{
        if (!isset($_SESSION['cart'])) {
            return true;
        }
        return false;
    }

    public function updateQuantity($productId, $quantity){
     if (isset($_SESSION['cart'])) {
            $_SESSION['cart'][$productId]['quantity']=$quantity;   
       } 
    }
    
    public function calculateLoyaltyPoints(){
        $userPointActuel=$_SESSION['user_points'];
        $pricetotal=$this->getTotal();
        return ($pricetotal*0.1);
    }


    public function clear(){
        if (isset($_SESSION['cart'])) {
        unset($_SESSION['cart']);
        }
    }
      
}