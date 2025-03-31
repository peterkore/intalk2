<?php

namespace Webshop\Controllers;

use Webshop\BaseController;
use Webshop\Model\Category;
use Webshop\Model\Product;
use Doctrine\ORM\EntityManagerInterface;

class CategoryController extends BaseController
{
    public function show(int $id): void
    {
        $category = $this->entityManager->getRepository(Category::class)->find($id);
        
        if (!$category) {
            header('Location: /');
            exit;
        }

        $products = $category->getProducts();

        require __DIR__ . '/../Templates/category/show.php';
    }
} 