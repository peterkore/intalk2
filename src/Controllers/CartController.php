<?php

namespace Webshop\Controllers;

use Webshop\View;
use Webshop\Model\Product;
use Webshop\BaseController;

class CartController extends BaseController
{
    public function index()
    {
        // Ha még nincs kosár a session-ben, létrehozzuk
        if(!isset($_SESSION['cart'])){
            $_SESSION['cart'] = [];
        }
        // Kigyűjtjük a kosárban lévő termékek id-jét
        $productIds = array_keys($_SESSION['cart']);
        $productRepository = $this->entityManager->getRepository(Product::class);
        // A product id-k alapján lekérjük a termékeket a db-ből
        $products = $productRepository->createQueryBuilder('p')
            ->where('p.id IN (:ids)')
            ->setParameter('ids', $productIds)
            ->getQuery()
            ->getResult();
        //Rendereljük a view-t a termékek és a kosár adataival
        echo (new View())->render('cart.php', [
            'title' => 'Kosár oldal',
            'products' => $products,
            'cart' => $_SESSION['cart']
        ]);
    }

    public function addToCart($id)
    {
        // Adott azonosítójú termék lekérdezése az adatbázisból
        $product = $this->entityManager->find(Product::class, $id);
        //Ellenőrizni, hogy létezik e a termék
        if (!$product) {
            $this->sendAjaxResponse('A megadott azonosítójú termék nem létezik');
        }
        // Lekérjük az adott termék készletét
        $stockQuantity = $product->getStock();
        // GET paraméterből kivesszük a kosárba teendő termékmennyiséget (ha nem kaptunk adatot, 1 db termékkel dolgozunk)
        $requestedQuantity = $_GET['quantity'] ?? 1;
        // Megvizsgáljuk, hogy rendelkezésre áll e a kért mennyiség a készletben
        if ($requestedQuantity > $stockQuantity) {
            $this->sendAjaxResponse('Nincs elegendő készlet mennyiség a kosárba rakáshoz');
        }
        // Ha negatív mennyiséget kaptunk (tehát csökkentjük a kosárban található mennyiséget a termékből), vizsgáljuk, hogy csak annyit adhassunk vissza, amennyi a kosarunkban volt
        if ($requestedQuantity < 0 && (!isset($_SESSION['cart'][$id]) || $_SESSION['cart'][$id] < -$requestedQuantity)) {
            $this->sendAjaxResponse('A kosárban nem található a hivatkozott termék');
        }
        // Ha a termék nincs a kosárban, akkor tegyük bele, egyébként növeljük/csökkentjük a mennyiségét
        if (!isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] = $requestedQuantity;
        } else {
            $_SESSION['cart'][$id] += $requestedQuantity;
        }
        // Ha a kosárban az adott termék mennyisége 0, akkor töröljük a kosárból
        if ($_SESSION['cart'][$id] == 0){
            unset($_SESSION['cart'][$id]);
        }
        // Készlet növelése/csökkentése az adatbázisban
        if ($requestedQuantity <= $stockQuantity) {
            $product->setStock($stockQuantity - $requestedQuantity);
            // Változtatások mentése
            $this->entityManager->flush();
        }
    }
}
