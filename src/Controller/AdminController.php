<?php

namespace Webshop\Controller;

use Doctrine\ORM\EntityManager;
use Webshop\Controllers\BaseController;
use Webshop\Entity\User;
use Webshop\Entity\Product;
use Webshop\Entity\Order;
use Webshop\Entity\Category;
use Webshop\Model\ProductAttribute;
use Webshop\Core\EntityManagerFactory;

class AdminController extends BaseController
{
    protected EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager);
        $this->entityManager = EntityManagerFactory::getEntityManager();
    }

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

            if ($user && $password === $user->getPassword()) {
                $_SESSION['admin_id'] = $user->getId();
                $_SESSION['admin_email'] = $user->getEmail();
                error_log('Sikeres bejelentkezés, átirányítás a dashboard-ra');
                header('Location: /admin/dashboard');
                exit;
            } else {
                $error = 'Hibás email vagy jelszó!';
                error_log('Sikertelen bejelentkezés');
                require __DIR__ . '/../View/admin/login.php';
            }
        } else {
            error_log('Login űrlap megjelenítése');
            require __DIR__ . '/../View/admin/login.php';
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

        $totalProducts = $this->entityManager->getRepository(Product::class)->count([]);
        $totalOrders = $this->entityManager->getRepository(Order::class)->count([]);
        $totalUsers = $this->entityManager->getRepository(User::class)->count([]);
        $totalCategories = $this->entityManager->getRepository(Category::class)->count([]);

        require __DIR__ . '/../View/admin/dashboard.php';
    }

    public function products(): void
    {
        $this->checkAdminAuth();

        $products = $this->entityManager->getRepository(Product::class)->findAll();
        require __DIR__ . '/../View/admin/products.php';
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

            header('Location: /admin/products');
            exit;
        }

        $categories = $this->entityManager->getRepository(Category::class)->findAll();
        require __DIR__ . '/../View/admin/product_edit.php';
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

            header('Location: /admin/products');
            exit;
        }

        $categories = $this->entityManager->getRepository(Category::class)->findAll();
        require __DIR__ . '/../View/admin/product_edit.php';
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

    public function orders(): void
    {
        $this->checkAdminAuth();

        $orders = $this->entityManager->getRepository(Order::class)->findAll();
        require __DIR__ . '/../View/admin/orders.php';
    }

    public function viewOrder(int $orderId): void
    {
        $this->checkAdminAuth();

        $order = $this->entityManager->getRepository(Order::class)->find($orderId);
        if (!$order) {
            header('Location: /admin/orders');
            exit;
        }

        require __DIR__ . '/../View/admin/order_view.php';
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

    private function updateProductFromRequest(Product $product, Request $request): void
    {
        $product->setName($request->getPost('name'));
        $product->setSku($request->getPost('sku'));
        $product->setPrice((float)$request->getPost('price'));
        $product->setStock((int)$request->getPost('stock'));
        $product->setBrand($request->getPost('brand'));
        $product->setModel($request->getPost('model'));
        $product->setDescription($request->getPost('description'));
        $product->setIsActive($request->getPost('isActive') === 'on');

        $categoryId = $request->getPost('category');
        if ($categoryId) {
            $category = $this->entityManager->getRepository(Category::class)->find($categoryId);
            if ($category) {
                $product->setCategory($category);
            }
        }

        // Attribútumok kezelése
        $attributeNames = $request->getPost('attribute_names', []);
        $attributeValues = $request->getPost('attribute_values', []);

        // Régi attribútumok törlése
        foreach ($product->getAttributes() as $attribute) {
            $this->entityManager->remove($attribute);
        }

        // Új attribútumok hozzáadása
        foreach ($attributeNames as $index => $name) {
            if (!empty($name) && isset($attributeValues[$index])) {
                $attribute = new ProductAttribute();
                $attribute->setName($name);
                $attribute->setValue($attributeValues[$index]);
                $attribute->setProduct($product);
                $this->entityManager->persist($attribute);
            }
        }
    }
} 