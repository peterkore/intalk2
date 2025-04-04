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
} 