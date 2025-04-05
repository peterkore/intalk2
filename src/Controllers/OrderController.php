<?php

namespace Webshop\Controllers;

use Webshop\View;
use Webshop\Model\User;
use Webshop\Model\Order;
use Webshop\BaseController;

class OrderController extends BaseController
{
    public function index(): void
    {
        // Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
        $this->checkUserIsLoggedin();

        echo (new View())->render('order/index.php', [
            'title' => 'Rendelések - Állatwebshop',
            'user' => $this->entityManager->getRepository(User::class)->find($_SESSION['user']['loggedin_id']),
            'orders' => $this->entityManager->getRepository(Order::class)->findBy(['user' => $_SESSION['user']['loggedin_id']], ['createdAt' => 'DESC']),
        ]);
    }

    public function show(int $id): void
    {
        // Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
        $userId = $this->checkUserIsLoggedin();

        $order = $this->entityManager->getRepository(Order::class)->find($id);
        
        // Ellenőrizzük, hogy a felhasználó rendelése létezik-e
        if (!$order || $order->getUser()->getId() !== $userId) {
            header('Location: /order');
            exit;
        }

        echo (new View())->render('order/show.php', [
            'title' => 'Rendelés - Állatwebshop',
            'order' => $order,
        ]);
    }
} 