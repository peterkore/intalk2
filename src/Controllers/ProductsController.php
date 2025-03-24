<?php

namespace Webshop\Controllers;

use Webshop\View;
use Webshop\Model\Product;
use Webshop\BaseController;

class ProductsController extends BaseController
{
    public function index()
    {
        $productRepository = $this->entityManager->getRepository(Product::class);
        echo (new View())->render('products.php', [
            'products' => $productRepository->findAll()
        ]);
    }
}