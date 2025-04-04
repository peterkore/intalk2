<?php

namespace Webshop\Controllers;

use Webshop\View;
use Webshop\BaseController;
use Webshop\Model\Category;

class CategoryController extends BaseController
{
    public function show(int $id): void
    {
        $category = $this->entityManager->getRepository(Category::class)->find($id);
        
        if (!$category) {
            header('Location: /');
            exit;
        }

        echo (new View())->render('category/show.php', [
            'title' => 'Állatwebshop - kategória',
            'products' => $category->getProducts(),
            'category' => $category
        ]);
    }
} 