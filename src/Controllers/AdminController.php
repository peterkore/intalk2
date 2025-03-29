<?php

namespace Webshop\Controllers;

use Webshop\View;
use Webshop\Model\User;
use Webshop\Model\Order;
use Webshop\Core\Request;
use Webshop\Model\Product;
use Webshop\BaseController;
use Webshop\Model\Category;
use Webshop\Model\ProductAttribute;



class AdminController extends BaseController
{

    public function login(): void
    {
        // Debug információk
        error_log('Login metódus meghívva');
        error_log('Session állapot: ' . print_r($_SESSION, true));
        error_log('POST adatok: ' . print_r($_POST, true));

        if (isset($_SESSION['admin_id'])) {
            error_log('Már be van jelentkezve, átirányítás a dashboard-ra');
            header('Location: /admin/dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            error_log('Bejelentkezési kísérlet: ' . $email);

            $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($user) {
                error_log('Felhasználó található: ' . $user->getId());
                error_log('Jelszó ellenőrzés: ' . ($password === $user->getPassword() ? 'Sikeres' : 'Sikertelen'));
            } else {
                error_log('Felhasználó nem található');
            }

            if ($user && password_verify($password, $user->getPassword())) {
                $_SESSION['admin_id'] = $user->getId();
                $_SESSION['admin_email'] = $user->getEmail();
                error_log('Sikeres bejelentkezés, átirányítás a dashboard-ra');
                header('Location: /admin/dashboard');
                exit;
            } else {
                error_log('Sikertelen bejelentkezés');
                echo (new View())->render('Admin/login.php', [
                    'error' => 'Hibás email vagy jelszó!'
                ]);
            }
        } else {
            error_log('Login űrlap megjelenítése');
            echo (new View())->render('Admin/login.php');
        }
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: /admin/login');
        exit;
    }

    public function dashboard(): void
    {
        $this->checkAdminAuth();

        echo (new View())->render('Admin/dashboard.php', [
            'totalProducts' => $this->entityManager->getRepository(Product::class)->count([]),
            'totalOrders' => $this->entityManager->getRepository(Order::class)->count([]),
            'totalUsers' => $this->entityManager->getRepository(User::class)->count([]),
            'totalCategories' => $this->entityManager->getRepository(Category::class)->count([])
        ]);
    }

    public function products($method = '', $id = ''): void
    {
        $this->checkAdminAuth();
        switch ($method) {
            case 'new':
                $this->newProduct();
                break;
            case 'edit':
                $this->editProduct($id);
                break;
            case 'delete':
                $this->deleteProduct($id);
                break;
            case 'updateOrderStatus':
                $this->updateOrderStatus($id);
                break;

            default:
                echo (new View())->render('Admin/products.php', [
                    'title' => 'Kezdőlap - Állatwebshop',
                    'products' => $this->entityManager->getRepository(Product::class)->findAll()
                ]);
                break;
        }
    }


    public function orders($method = '', $id = ''): void
    {
        $this->checkAdminAuth();
        switch ($method) {

            case 'updateOrderStatus':
                $this->updateOrderStatus($id);
                break;

            case 'viewOrder':
                $this->viewOrder($id);
                break;

            default:
                echo (new View())->render('Admin/orders.php', [
                    'title' => 'Rendelések - Állatwebshop',
                    'orders' => $this->entityManager->getRepository(Order::class)->findAll()
                ]);
                break;
        }
    }

    public function newProduct(): void
    {
        $this->checkAdminAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product = new Product();
            $product->setName($_POST['name']);
            $product->setSku($_POST['sku']);
            $product->setPrice($_POST['price']);
            $product->setStock($_POST['stock']);
            $product->setCategory($this->entityManager->getRepository(Category::class)->find($_POST['category_id']));
            $product->setBrand($_POST['brand']);
            $product->setModel($_POST['model']);
            $product->setDescription($_POST['description']);
            $product->setIsActive(isset($_POST['is_active']));

            $this->entityManager->persist($product);
            $this->entityManager->flush();

            $this->updateProductAttributes($product);

            header('Location: /admin/products');
            exit;
        }

        echo (new View())->render('Admin/product_edit.php', [
            'title' => 'Kezdőlap - Állatwebshop',
            'categories' => $this->entityManager->getRepository(Category::class)->findAll(),
            'product' => false
        ]);
    }

    public function editProduct(int $productId): void
    {
        $this->checkAdminAuth();

        $product = $this->entityManager->getRepository(Product::class)->find($productId);
        if (!$product) {
            header('Location: /admin/products');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product->setName($_POST['name']);
            $product->setSku($_POST['sku']);
            $product->setPrice($_POST['price']);
            $product->setStock($_POST['stock']);
            $product->setCategory($this->entityManager->getRepository(Category::class)->find($_POST['category_id']));
            $product->setBrand($_POST['brand']);
            $product->setModel($_POST['model']);
            $product->setDescription($_POST['description']);
            $product->setIsActive(isset($_POST['is_active']));

            $this->entityManager->flush();

            $this->updateProductAttributes($product);

            header('Location: /admin/products');
            exit;
        }
        echo (new View())->render('Admin/product_edit.php', [
            'title' => 'Kezdőlap - Állatwebshop',
            'categories' => $this->entityManager->getRepository(Category::class)->findAll(),
            'product' => $this->entityManager->getRepository(Product::class)->find($productId)
        ]);
    }

    public function deleteProduct(int $productId): void
    {
        $this->checkAdminAuth();

        $product = $this->entityManager->getRepository(Product::class)->find($productId);
        if ($product) {
            $this->entityManager->remove($product);
            $this->entityManager->flush();
        }

        header('Location: /admin/products');
        exit;
    }

    public function viewOrder(int $orderId): void
    {
        $this->checkAdminAuth();

        $order = $this->entityManager->getRepository(Order::class)->find($orderId);
        if (!$order) {
            header('Location: /admin/orders');
            exit;
        }

        echo (new View())->render('Admin/order_view.php', [
            'title' => 'Rendelés - Állatwebshop',
            'order' => $this->entityManager->getRepository(Order::class)->find($orderId)
        ]);
    }

    public function updateOrderStatus(int $orderId): void
    {
        $this->checkAdminAuth();

        $order = $this->entityManager->getRepository(Order::class)->find($orderId);
        if ($order) {
            $order->setStatus($_POST['status']);
            $this->entityManager->flush();
        }

        header('Location: /admin/orders');
        exit;
    }

    private function checkAdminAuth(): void
    {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: /admin/login');
            exit;
        }
    }

    private function updateProductAttributes(Product $product, Request $request = new Request()): void
    {
        // Attribútumok kezelése
        $attributeNames = $request->getPost('attribute_names', []);
        $attributeValues = $request->getPost('attribute_values', []);

        // Régi attribútumok törlése
        foreach ($product->getAttributes() as $attribute) {
            $this->entityManager->remove($attribute);
        }

        $this->entityManager->flush();


        // Új attribútumok hozzáadása
        foreach ($attributeNames as $index => $name) {
            if (!empty($name) && isset($attributeValues[$index])) {
                $attribute = new ProductAttribute();
                $attribute->setName($name);
                $attribute->setValue($attributeValues[$index]);
                $attribute->setProduct($product);
                $this->entityManager->persist($attribute);
                $this->entityManager->flush();
            }
        }
    }

    // private function updateProductFromRequest(Product $product, Request $request): void
    // {
    //     $product->setName($request->getPost('name'));
    //     $product->setSku($request->getPost('sku'));
    //     $product->setPrice((float)$request->getPost('price'));
    //     $product->setStock((int)$request->getPost('stock'));
    //     $product->setBrand($request->getPost('brand'));
    //     $product->setModel($request->getPost('model'));
    //     $product->setDescription($request->getPost('description'));
    //     $product->setIsActive($request->getPost('isActive') === 'on');

    //     $categoryId = $request->getPost('category');
    //     if ($categoryId) {
    //         $category = $this->entityManager->getRepository(Category::class)->find($categoryId);
    //         if ($category) {
    //             $product->setCategory($category);
    //         }
    //     }

    //     // Attribútumok kezelése
    //     $attributeNames = $request->getPost('attribute_names', []);
    //     $attributeValues = $request->getPost('attribute_values', []);

    //     // Régi attribútumok törlése
    //     foreach ($product->getAttributes() as $attribute) {
    //         $this->entityManager->remove($attribute);
    //     }

    //     // Új attribútumok hozzáadása
    //     foreach ($attributeNames as $index => $name) {
    //         if (!empty($name) && isset($attributeValues[$index])) {
    //             $attribute = new ProductAttribute();
    //             $attribute->setName($name);
    //             $attribute->setValue($attributeValues[$index]);
    //             $attribute->setProduct($product);
    //             $this->entityManager->persist($attribute);
    //             $this->entityManager->flush();
    //         }
    //     }
    // }
}
