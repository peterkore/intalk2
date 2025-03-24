<?php

namespace Webshop\Controllers;

use Webshop\View;
use Webshop\Model\Product;
use Webshop\BaseController;

class ProductController extends BaseController
{
    public function view($id = '')
    {
        if(empty($id) && isset($_GET['id'])){
            $id = $_GET['id'];
        }
        echo (new View())->render('product.php', [
            'product' => $this->entityManager->find(Product::class, $id) ?? new class {}
        ]);
    }
}