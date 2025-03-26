<?php

namespace Webshop\Controllers;

use Webshop\Model\Product;
use Webshop\View;
use Webshop\BaseController;

class ProductsController extends BaseController
{
    public function index(): void
    {
        $productRepository = $this->entityManager->getRepository(Product::class);
        $products = $productRepository->findAll();

        echo (new View())->render('products/index.php', [
            'title' => 'Termékek - Állatwebshop',
            'products' => $products
        ]);
    }
}