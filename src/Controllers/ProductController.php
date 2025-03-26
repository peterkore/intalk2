<?php

namespace Webshop\Controllers;

use Webshop\Model\Product;
use Webshop\View;
use Webshop\BaseController;

class ProductController extends BaseController
{
    public function view(int $id): void
    {
        $productRepository = $this->entityManager->getRepository(Product::class);
        $product = $productRepository->find($id);

        if (!$product) {
            $this->handleError();
            return;
        }
        echo (new View())->render('product.php', [
            'title' => 'Terméklap',
            'product' => $this->entityManager->find(Product::class, $id) ?? new class {}
        ]);
    }

    private function handleError(): void
    {
        (new ErrorController($this->entityManager))->index();
    }
}