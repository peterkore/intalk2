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
        echo (new View())->render('products.php', [
            'title' => 'Termékek - Állatwebshop',
            'products' => $productRepository->findAll()
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

    //a keresésre saját metódus létrehozása a ProductsControllerbe
    public function searchProducts(string $searchTerm): array
    {
        return $this -> createQueryBuilder('p')
            ->where('p.name LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();
    }
}
