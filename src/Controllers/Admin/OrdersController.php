<?php

namespace Webshop\Controllers\Admin;

use Webshop\View;
use Webshop\Model\Order;
use Webshop\BaseController;

class OrdersController extends BaseController
{
    public function index(): void
    {
        $this->checkAdminAuth();
        echo (new View())->render('Admin/orders.php', [
            'title' => 'Rendelések - Állatwebshop',
            'orders' => $this->entityManager->getRepository(Order::class)->findAll()
                ]);
    }
}