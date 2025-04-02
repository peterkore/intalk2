<?php

namespace Webshop\Controllers;

use Webshop\View;
use Webshop\Model\User;
use Webshop\Model\Order;
use Webshop\Model\Address;
use Webshop\Model\Product;
use Webshop\BaseController;
use Webshop\Model\OrderItem;

class OrderController extends BaseController
{
    public function index(): void
    {
        // Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
        if (!isset($_SESSION['user']['loggedin_id'])) {
            header('Location: /');
            exit;
        }

        echo (new View())->render('order/index.php', [
            'title' => 'Rendelések - Állatwebshop',
            'user' => $this->entityManager->getRepository(User::class)->find($_SESSION['user']['loggedin_id']),
            'orders' => $this->entityManager->getRepository(Order::class)->findBy(['user' => $_SESSION['user']['loggedin_id']], ['createdAt' => 'DESC']),
        ]);
    }

    public function show(int $id): void
    {
        // Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
        if (!isset($_SESSION['user']['loggedin_id'])) {
            header('Location: /');
            exit;
        }

        $order = $this->entityManager->getRepository(Order::class)->find($id);
        
        // Ellenőrizzük, hogy a rendelés létezik-e és a bejelentkezett felhasználóé-e
        if (!$order || $order->getUser()->getId() !== $_SESSION['user']['loggedin_id']) {
            header('Location: /');
            exit;
        }

        echo (new View())->render('order/show.php', [
            'title' => 'Rendelés - Állatwebshop',
            'order' => $this->entityManager->getRepository(Order::class)->find($id),
        ]);
    }

    public function create(): void
    {
        // Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
        if (!isset($_SESSION['user']['loggedin_id'])) {
            header('Location: /');
            exit;
        }

        // Ellenőrizzük, hogy van-e termék a kosárban
        if (empty($_SESSION['cart'])) {
            header('Location: /cart');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->entityManager->getRepository(User::class)->find($_SESSION['user']['loggedin_id']);
            
            // Létrehozzuk a rendelést
            $order = new Order();
            $order->setUser($user);
            $order->setStatus(Order::STATUS_PENDING);
            $order->setPaymentMethod($_POST['payment_method']);
            $order->setShippingMethod($_POST['shipping_method']);
            
            // Beállítjuk a számlázási és szállítási címet
            $billingAddress = $this->entityManager->getRepository(Address::class)->find($_POST['billing_address_id']);
            $shippingAddress = $this->entityManager->getRepository(Address::class)->find($_POST['shipping_address_id']);
            
            if (!$billingAddress || !$shippingAddress || 
                $billingAddress->getUser()->getId() !== $user->getId() || 
                $shippingAddress->getUser()->getId() !== $user->getId()) {
                header('Location: /order/create');
                exit;
            }
            
            $order->setBillingAddress($billingAddress);
            $order->setShippingAddress($shippingAddress);
            
            $totalAmount = 0;
            
            // Hozzáadjuk a termékeket a rendeléshez
            foreach ($_SESSION['cart'] as $productId => $quantity) {
                $product = $this->entityManager->getRepository(Product::class)->find($productId);
                
                if (!$product || $product->getStock() < $quantity) {
                    header('Location: /cart');
                    exit;
                }
                
                $orderItem = new OrderItem();
                $orderItem->setOrder($order);
                $orderItem->setProduct($product);
                $orderItem->setQuantity($quantity);
                $orderItem->setPrice($product->getPrice());
                
                $order->addItem($orderItem);
                $totalAmount += $orderItem->getSubtotal();
                
                // Csökkentjük a készletet
                $product->setStock($product->getStock() - $quantity);
            }
            
            $order->setTotalAmount($totalAmount);
            
            // Mentjük a rendelést
            $this->entityManager->persist($order);
            $this->entityManager->flush();
            
            // Töröljük a kosár tartalmát
            unset($_SESSION['cart']);
            
            // Átirányítjuk a rendelés részletes nézetére
            header('Location: /order/show/' . $order->getId());
            exit;
        }
        
        // Ha GET kérés, megjelenítjük a rendelés űrlapot
        
        echo (new View())->render('order/create.php', [
            'title' => 'Rendelés leadás - Állatwebshop',
            'user' => $this->entityManager->getRepository(User::class)->find($_SESSION['user']['loggedin_id']),
            'addresses' => $this->entityManager->getRepository(Address::class)->findBy(['user' => $_SESSION['user']['loggedin_id']]),
        ]);
    }
} 