<?php

namespace Webshop\Controllers\Admin;

use Webshop\View;
use Webshop\Model\Product;
use Webshop\BaseController;

class ProductsController extends BaseController
{
    public function index(): void
    {
        $this->checkAdminAuth();
        echo (new View())->render('Admin/products.php', [
            'title' => 'Kezdőlap - Állatwebshop',
            'products' => $this->entityManager->getRepository(Product::class)->findAll()
        ]);
    }
}