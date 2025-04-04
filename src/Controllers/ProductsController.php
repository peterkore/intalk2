<?php

namespace Webshop\Controllers;

use Webshop\Model\Product;
use Webshop\View;
use Webshop\BaseController;

class ProductsController extends BaseController
{
    // Termékek lap
    public function index(): void
    {
        $productRepository = $this->entityManager->getRepository(Product::class);
        $products = $productRepository->findAll();
        
        // Képek elérési útjainak beállítása
        foreach ($products as $product) {
            $product->imagePath = $product->getImagePath() ?: 'images/no-image-thumb.png';
            $product->thumbnailPath = $product->getThumbnailPath() ?: 'images/no-image-thumb.jpg';
        }

        echo (new View())->render('products.php', [
            'title' => 'Termékek - Állatwebshop',
            'products' => $products
        ]);
    }

    // Minta json formátumú adat visszadására pl. ajax kérésekhez (/products/jsonproduct)
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

        // Válasz beállítása JSON formátumban
        header("Content-Type: application/json");
        echo json_encode($responseData);
    }
}
