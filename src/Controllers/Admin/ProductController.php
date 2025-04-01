<?php

namespace Webshop\Controllers\Admin;

use Webshop\View;
use Webshop\Core\Request;
use Webshop\Model\Product;
use Webshop\BaseController;
use Webshop\Model\Category;
use Webshop\Model\ProductAttribute;

class ProductController extends BaseController
{

    public function new(): void
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
            'title' => 'Új termék - Petshop Admin',
            'categories' => $this->entityManager->getRepository(Category::class)->findAll(),
            'product' => false
        ]);
    }

    public function edit(int $productId): void
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
            'title' => 'Termék szerkesztése - Petshop Admin',
            'categories' => $this->entityManager->getRepository(Category::class)->findAll(),
            'product' => $this->entityManager->getRepository(Product::class)->find($productId)
        ]);
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

    public function delete(int $productId): void
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

}
