<?php

namespace Webshop\Controllers\Admin;

use Webshop\View;
use Webshop\Model\Order;
use Webshop\BaseController;

class OrderController extends BaseController
{
    public function view(int $orderId): void
    {
        $this->checkAdminAuth();

        $order = $this->entityManager->getRepository(Order::class)->find($orderId);
        if (!$order) {
            header('Location: /admin/orders');
            exit;
        }

        echo (new View())->render('Admin/order_view.php', [
            'title' => 'Rendelés - - Petshop Admin',
            'order' => $order,
        ]);
    }

        public function updateOrderStatus(int $orderId): void
    {
        $this->checkAdminAuth();

        $order = $this->entityManager->getRepository(Order::class)->find($orderId);
        if ($order) {
            $order->setStatus($_POST['status']);
            $this->entityManager->flush();
        }

        header('Location: /admin/orders');
        exit;
    }
}