<?php

namespace Webshop\Controllers;

use Webshop\View;
use Webshop\Model\Product;
use Webshop\Model\User;
use Webshop\Model\Order;
use Webshop\Model\OrderItem;
use Webshop\BaseController;

class CartController extends BaseController
{
    public function index()
    {
        // Ha még nincs kosár a session-ben, létrehozzuk
        if(!isset($_SESSION['cart'])){
            $_SESSION['cart'] = [];
        }
        // Kigyűjtjük a kosárban lévő termékek id-jét
        $productIds = array_keys($_SESSION['cart']);
        $productRepository = $this->entityManager->getRepository(Product::class);
        // A product id-k alapján lekérjük a termékeket a db-ből
        $products = $productRepository->createQueryBuilder('p')
            ->where('p.id IN (:ids)')
            ->setParameter('ids', $productIds)
            ->getQuery()
            ->getResult();
        //Rendereljük a view-t a termékek és a kosár adataival
        echo (new View())->render('cart.php', [
            'title' => 'Kosár oldal',
            'products' => $products,
            'cart' => $_SESSION['cart']
        ]);
    }

    public function addToCart($id)
    {
        // Ellenőrizzük, hogy AJAX kérés-e
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Csak AJAX kérések engedélyezettek']);
            exit;
        }

        // Adott azonosítójú termék lekérdezése az adatbázisból
        $product = $this->entityManager->find(Product::class, $id);
        
        //Ellenőrizni, hogy létezik e a termék
        if (!$product) {
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['error' => 'A megadott azonosítójú termék nem létezik']);
            exit;
        }

        // Lekérjük az adott termék készletét
        $stockQuantity = $product->getStock();
        
        // POST paraméterből kivesszük a kosárba teendő termékmennyiséget (ha nem kaptunk adatot, 1 db termékkel dolgozunk)
        $requestedQuantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        
        // Megvizsgáljuk, hogy rendelkezésre áll e a kért mennyiség a készletben
        if ($requestedQuantity > $stockQuantity) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Nincs elegendő készlet mennyiség a kosárba rakáshoz']);
            exit;
        }

        // Ha negatív mennyiséget kaptunk (tehát csökkentjük a kosárban található mennyiséget a termékből), vizsgáljuk, hogy csak annyit adhassunk vissza, amennyi a kosarunkban volt
        if ($requestedQuantity < 0 && (!isset($_SESSION['cart'][$id]) || $_SESSION['cart'][$id] < -$requestedQuantity)) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'A kosárban nem található a hivatkozott termék']);
            exit;
        }

        // Ha a termék nincs a kosárban, akkor tegyük bele, egyébként növeljük/csökkentjük a mennyiségét
        if (!isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] = $requestedQuantity;
        } else {
            $_SESSION['cart'][$id] += $requestedQuantity;
        }

        // Ha a kosárban az adott termék mennyisége 0, akkor töröljük a kosárból
        if ($_SESSION['cart'][$id] == 0){
            unset($_SESSION['cart'][$id]);
        }

        // Készlet növelése/csökkentése az adatbázisban
        if ($requestedQuantity <= $stockQuantity) {
            $product->setStock($stockQuantity - $requestedQuantity);
            // Változtatások mentése
            $this->entityManager->flush();
        }

        // Visszaadjuk a sikeres választ
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'A termék sikeresen hozzáadva a kosárhoz',
            'cart' => $_SESSION['cart']
        ]);
    }

    public function checkout()
    {
        // Ellenőrizzük, hogy van-e termék a kosárban
        if (empty($_SESSION['cart'])) {
            header('Location: /cart');
            exit;
        }

        // Ha nincs bejelentkezett felhasználó, létrehozunk egy vendég felhasználót
        if (!isset($_SESSION['user'])) {
            $guestUser = new User();
            $guestUser->setEmail('guest_' . uniqid() . '@example.com');
            $guestUser->setPassword(password_hash(uniqid(), PASSWORD_DEFAULT));
            $guestUser->setName('Vendég felhasználó');
            $guestUser->setRole('user');
            
            $this->entityManager->persist($guestUser);
            $this->entityManager->flush();
            
            $_SESSION['user'] = $guestUser;
        }

        // Rendereljük a checkout oldalt
        echo (new View())->render('cart/checkout.php', [
            'title' => 'Rendelés véglegesítése',
            'user' => $_SESSION['user']
        ]);
    }

    public function placeOrder()
    {
        // Ellenőrizzük, hogy van-e termék a kosárban
        if (empty($_SESSION['cart'])) {
            header('Location: /cart');
            exit;
        }

        // Ellenőrizzük, hogy van-e bejelentkezett felhasználó
        if (!isset($_SESSION['user'])) {
            header('Location: /cart/checkout');
            exit;
        }

        // Létrehozzuk a rendelést
        $order = new Order();
        $order->setUser($_SESSION['user']);
        $order->setStatus('pending');
        $order->setTotalAmount(0);
        $order->setCreatedAt(new \DateTime());

        // Hozzáadjuk a rendeléshez a termékeket
        $totalAmount = 0;
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $product = $this->entityManager->find(Product::class, $productId);
            if ($product) {
                $orderItem = new OrderItem();
                $orderItem->setOrder($order);
                $orderItem->setProduct($product);
                $orderItem->setQuantity($quantity);
                $orderItem->setPrice($product->getPrice());
                
                $this->entityManager->persist($orderItem);
                $totalAmount += $product->getPrice() * $quantity;
            }
        }

        $order->setTotalAmount($totalAmount);
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        // Töröljük a kosár tartalmát
        unset($_SESSION['cart']);

        // Átirányítjuk a felhasználót a rendelés részletes nézetére
        header('Location: /order/show/' . $order->getId());
        exit;
    }
}
