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
        echo (new View())->render('products.php', [
            'title' => 'Termék lista',
            'products' => $productRepository->findAll()
        ]);
    }

    public function jsonProduct()
    {
        $productRepository = $this->entityManager->getRepository(Product::class);
        // Összes termék lekérdezése az adatbázisból
        $products = $productRepository->findAll();
        // A product objektum átalakítása tömb formátummá a json válaszhoz
        $responseData = array_map(function ($product) {
            return [
                'id' => $product->getId(),
                'name' => $product->getName(),
            ];
        }, $products);

        // Json válasz visszaadásához a jsonResponse.php-t töltjük be, amely json 
        // Válasz beállítása JSON formátumban
        header("Content-Type: application/json");
        echo json_encode($responseData);
    }
}
