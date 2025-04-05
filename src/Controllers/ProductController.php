<?php

namespace Webshop\Controllers;

use Webshop\View;

use Webshop\Model\Product;
use Webshop\BaseController;

class ProductController extends BaseController {
    public function view($id) {
        $product = $this->entityManager->getRepository(Product::class)->find($id);
        
        if (!$product) {
            header('Location: /404');
            exit;
        }

        // Kapcsolódó termékek lekérése (ugyanabból a kategóriából)
        $relatedProducts = $this->entityManager->getRepository(Product::class)
            ->createQueryBuilder('p')
            ->where('p.category = :category')
            ->andWhere('p.id != :id')
            ->andWhere('p.stock > 0')
            ->setParameter('category', $product->getCategory())
            ->setParameter('id', $product->getId())
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();

        echo (new View())->render('product.php', [
            'title' => 'Termék - Állatwebshop',
            'relatedProducts' => $relatedProducts,
            'product' => $product,

        ]);
    }

    // Keresés végrahajtása a name, description mezőkben
    // a keresési funkció meghívása a Controllerből
    public function search($search) {
        //$search = $_GET['search'] ?? '';
        
        if (empty($search)) {
            header('Location: /');
            exit;
        }
      // a termékekben keres, meghívja az entityManager-t a Product osztályt
        $products = $this->entityManager->getRepository(Product::class)
            ->createQueryBuilder('p')
            ->where('p.name LIKE :search')
            ->orWhere('p.description LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->andWhere('p.stock > 0')
            ->getQuery()
            ->getResult();
          //  return $this -> $result = getResult();
       // echo $products -> getDQL; 
        // a search.php Template meghívása
       // require_once __DIR__ . '/../Templates/product/search.php';
       // meghívja a product/searcht.php-t ami a View nézet részhez tartozik, ezáltal jelennek meg a találatok
       echo (new View())->render('product/search.php', [
        
        'products' => $products
    ]);
    }


    public function addToCart($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /');
            exit;
        }

        $product = $this->entityManager->getRepository(Product::class)->find($id);
        
        if (!$product) {
            echo json_encode(['success' => false, 'error' => 'A termék nem található']);
            exit;
        }

        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        
        if ($quantity < 1) {
            echo json_encode(['success' => false, 'error' => 'Érvénytelen mennyiség']);
            exit;
        }

        if ($product->getStock() < $quantity) {
            echo json_encode(['success' => false, 'error' => 'Nincs elég készlet']);
            exit;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] += $quantity;
        } else {
            $_SESSION['cart'][$id] = $quantity;
        }

        echo json_encode([
            'success' => true,
            'message' => 'A termék sikeresen hozzáadva a kosárhoz'
        ]);
    }
}
