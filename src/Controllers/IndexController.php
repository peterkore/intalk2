<?php

namespace Webshop\Controllers;

use Webshop\View;
use Webshop\BaseController;
use Webshop\Model\Category;
use Webshop\Model\Product;

class IndexController extends BaseController
{
    public function index(): void
    {
        try {
            $categories = $this->entityManager->getRepository(Category::class)->findAll();
            $productsByCategory = [];
    
            foreach ($categories as $category) {
                $products = $this->entityManager->getRepository(Product::class)
                    ->findBy(['category' => $category, 'isActive' => true]);
                
                $productsByCategory[$category->getId()] = $products;
            }
    
            echo (new View())->render('index.php', [
                'title' => 'Kezdőlap - Állatwebshop',
                'categories' => $categories,
                'productsByCategory' => $productsByCategory
            ]);
        } catch (\Throwable $th) {
            $this->handleError(500, $th->getMessage());
        }
       
    }
}