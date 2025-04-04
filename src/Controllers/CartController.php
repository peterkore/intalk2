<?php

namespace Webshop\Controllers;

use Webshop\View;

use Webshop\Model\User;
use Webshop\Model\Order;
use Webshop\Model\Address;
use Webshop\Model\Product;
use Webshop\BaseController;
use Webshop\Model\OrderItem;

class CartController extends BaseController
{
    public function index()
    {
        // Ha még nincs kosár a session-ben, létrehozzuk
        if (!isset($_SESSION['cart'])) {
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
        if ($_SESSION['cart'][$id] == 0) {
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
        if (!isset($_SESSION['user']['loggedin_id'])) {
            $user = new User();
            $user->setEmail('guest_' . uniqid() . '@example.com');
            $user->setPassword(password_hash(uniqid(), PASSWORD_DEFAULT));
            $user->setName('Vendég felhasználó');
            $user->setRole('ROLE_CUSTOMER');

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $_SESSION['user']['loggedin_id'] = $user->getId();
        } else {
            $user = $this->entityManager->find(User::class, $_SESSION['user']['loggedin_id']);
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


        // A user id alapján kérjük le a billing addresst a db-ből    
        $billingAddress = $user->getDefaultAddress('billing') ?? new Address();

        // A user id alapján kérjük le a shipping addresst a db-ből    
        $shippingAddress = $user->getDefaultAddress('shipping') ?? new Address();

        // Rendereljük a checkout oldalt
        echo (new View())->render('cart/checkout.php', [
            'title' => 'Rendelés véglegesítése',
            'user' => $user,
            'products' => $products,
            'cart' => $_SESSION['cart'],
            'billingAddress' => $billingAddress,
            'shippingAddress' => $shippingAddress,
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
        if (!isset($_SESSION['user']['loggedin_id'])) {
            header('Location: /cart/checkout');
            exit;
        }

        // Lekérjük a felhasználót az adatbázisból
        $user = $this->entityManager->getRepository(User::class)->find($_SESSION['user']['loggedin_id']);
        // Lekérjük a felhasználóhoz tartozó addresseket az adatbázisból
        //$billingAddress = $this->getOrSetAddress($user, 'billing');
        //$shippingAddress = $this->getOrSetAddress($user, 'shipping');

        // Létrehozzuk a rendelést
        $order = new Order();
        $order->setUser($user);
        $order->setBillingAddress($this->getOrSetAddress($user, 'billing'));
        $order->setShippingAddress($this->getOrSetAddress($user, 'shipping'));
        $order->setStatus('pending');
        $order->setTotalAmount(0);
        $order->setPaymentMethod($_POST['payment_method']);
        $order->setShippingMethod($_POST['shipping_method']);
        $order->getCreatedAt(new \DateTime());

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

    private function getOrSetAddress(User $user, string $type)
    {
        // Lekérés készítése a $_POST adatainak felhasználásával
        $criteria = [];
        if (isset($_POST[$type . '_street'])) {
            $criteria['street'] = $_POST[$type . '_street'];
        }
        if (isset($_POST[$type . '_zip_code'])) {
            $criteria['zipCode'] = $_POST[$type . '_zip_code'];
        }
        if (isset($_POST[$type . '_city'])) {
            $criteria['city'] = $_POST[$type . '_city'];
        }
        if (isset($_POST[$type . '_country'])) {
            $criteria['country'] = $_POST[$type . '_country'];
        }
        if (isset($_POST[$type . '_phone'])) {
            $criteria['phone'] = $_POST[$type . '_phone'];
        }

        $criteria['type'] = $type;
        $criteria['user'] = $user->getId();

        // Keresés az EntityManager használatával
        $userData = $this->entityManager->getRepository(Address::class)->findOneBy($criteria);

        if ($userData) {
            return $userData;
        }else{
            // Új entitás létrehozása, ha nincs találat
            $newUserData = new Address();
            $newUserData->setName($user->getName());
            $newUserData->setStreet($criteria['street']);
            $newUserData->setZipCode($criteria['zipCode']);
            $newUserData->setCity($criteria['city']);
            $newUserData->setCountry($criteria['country']);
            $newUserData->setPhone($criteria['phone']);
            $newUserData->setType($criteria['type']);
            $newUserData->setUser($user);

            // Mentés az adatbázisba
            $this->entityManager->persist($newUserData);
            $this->entityManager->flush();
            return $newUserData;
        }
    }
}
