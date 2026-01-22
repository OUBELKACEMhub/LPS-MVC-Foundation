<?php
namespace App\src\Controllers;
use App\src\Repositories\ProductRepository;
use App\src\Repositories\userRepository;
use App\src\Services\Cart;
class ShopController
{
   
    private \Twig\Environment $twig;
    private Cart $cart;
    private  $product;
    private $userRepo;

    
    public function __construct(\Twig\Environment $twig,$db)
    {
        $this->twig = $twig;
        $this->cart = new Cart();
        $this->product=new ProductRepository($db); 
        $this->userRepo=new userRepository($db);
          
 }
    private function getCommonData(): array
    {
        return [
            'user' => $_SESSION['user'],
            'cart_count' => $this->cart->getCount(),
            
        ];
    }


public function cart(): void
{
    
    if (!isset($_SESSION['user_id'])) {
        header('Location: /Loyalty%20Points%20System/App/public/login');
        exit;
    }
    $items = $this->cart->getItems(); 
    echo $this->twig->render('cart.html.twig', [
        'name'   => $_SESSION['user_name'] ?? 'Invité',
        'points' => $_SESSION['user_points'] ?? 0,
        'cart_items' => $items, 
        'cart_total' => $this->cart->getTotal(),
        'cart_count' => $this->cart->getCount()
    ]);
}

public function addToCart(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /Loyalty%20Points%20System/App/public');
        exit;
    }

    $productId = (int) ($_POST['product_id'] ?? 0);
    $quantity = (int) ($_POST['quantity'] ?? 1);
    $productData = $this->product->findById($productId);

    if ($productData && $quantity > 0) {

        $this->cart->addItem(
            $productData->getId(),
            $productData->getName(),
            $productData->getPrice(),
            $quantity
        );

     
        header('Location: /Loyalty%20Points%20System/App/public/cart'); 
        exit;
    } else {
        $_SESSION['error'] = "Produit introuvable.";
        header('Location: /Loyalty%20Points%20System/App/public');
        exit;
    }
}



public function updateCart(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Loyalty%20Points%20System/App/public/cart');
            exit;
        }

        $productId = (int) ($_POST['product_id'] ?? 0);
        $quantity = (int) ($_POST['quantity'] ?? 0);

        $this->cart->updateQuantity($productId, $quantity);

        header('Location: /Loyalty%20Points%20System/App/public/cart');
        exit;
    }

    public function removeFromCart(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Loyalty%20Points%20System/App/public/cart');
            exit;
        }

        $productId = (int) ($_POST['product_id'] ?? 0);
        $this->cart->removeItem($productId);

        header('Location: /Loyalty%20Points%20System/App/public/cart');
        exit;
    }
    

  public function checkout(): void
    {
        if ($this->cart->isEmpty()) {
            header('Location: /Loyalty%20Points%20System/App/public/cart');
            exit;
        }

        $checkoutError = $_SESSION['checkout_error'] ?? null;
        unset($_SESSION['checkout_error']);

        echo $this->twig->render('checkout.html.twig', array_merge(
            $this->getCommonData(),
            [
                'cart_items' => $this->cart->getItems(),
                'cart_total' => $this->cart->getTotal(),
                'loyalty_points' => $this->cart->calculateLoyaltyPoints(),
                'checkout_error' => $checkoutError,
                'loyalty_pointsActuel' => $_SESSION['user_points'] ?? 0 
            ]
        ));
    }
public function processCheckout(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Loyalty%20Points%20System/App/public/purchase-result');
            exit;
        }

        if ($this->cart->isEmpty()) {
            header('Location: /Loyalty%20Points%20System/App/public/cart');
            exit;
        }

        $cardName = trim($_POST['card_name'] ?? '');
        $cardNumber = trim($_POST['card_number'] ?? '');
        $cardExpiry = trim($_POST['card_expiry'] ?? '');
        $cardCvv = trim($_POST['card_cvv'] ?? '');

        if (empty($cardName) || empty($cardNumber) || empty($cardExpiry) || empty($cardCvv)) {
            $_SESSION['checkout_error'] = 'Veuillez remplir tous les champs.';
            header('Location: /Loyalty%20Points%20System/App/public/checkout'); // Shihna l-URL
            exit;
        }

        $cartTotal = $this->cart->getTotal();
        $pointsEarned = $this->cart->calculateLoyaltyPoints();
        
        $currentPoints = $_SESSION['user_points'] ?? 0;
        $newPointsTotal = $currentPoints + $pointsEarned;

        $_SESSION['user_points'] = $newPointsTotal;

        $this->userRepo->updatePoints($_SESSION['user_id'], $newPointsTotal);

        $_SESSION['last_purchase'] = [
            'items' => $this->cart->getItems(),
            'total' => $cartTotal,
            'points_earned' => $pointsEarned,
            'previous_points' => $currentPoints,
            'new_points' => $newPointsTotal,
            'date' => date('Y-m-d H:i:s')
        ];

        $this->cart->clear();

        header('Location: /Loyalty%20Points%20System/App/public/purchase-result');
        exit;
    }

    public function purchaseResult(): void
    {
        if (!isset($_SESSION['last_purchase'])) {
            header('Location: /Loyalty%20Points%20System/App/public');
            exit;
        }

        echo $this->twig->render('purchase_result.html.twig', array_merge(
            $this->getCommonData(),
            ['purchase' => $_SESSION['last_purchase']]
        ));
    }
}
