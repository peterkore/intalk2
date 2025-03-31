<?php

namespace Webshop\Controllers\Admin;

use Webshop\View;
use Webshop\Model\User;
use Webshop\Model\Order;
use Webshop\Model\Product;
use Webshop\BaseController;
use Webshop\Model\Category;



class DashboardController extends BaseController
{
    public function index(): void
    {
        $this->checkAdminAuth();

        echo (new View())->render('Admin/dashboard.php', [
            'totalProducts' => $this->entityManager->getRepository(Product::class)->count([]),
            'totalOrders' => $this->entityManager->getRepository(Order::class)->count([]),
            'totalUsers' => $this->entityManager->getRepository(User::class)->count([]),
            'totalCategories' => $this->entityManager->getRepository(Category::class)->count([])
        ]);
    }
    
}