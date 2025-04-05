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

            $this->handleImageUpload($product);
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

            $this->handleImageUpload($product);
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

    /**
     * Kép feltöltés kezelése
     * 
     * Ez a metódus felelős a termék képének feltöltéséért és feldolgozásáért.
     * A következő lépéseket végzi el:
     * 1. Ellenőrzi, hogy van-e feltöltött kép
     * 2. Létrehozza a szükséges könyvtárakat
     * 3. Ellenőrzi a fájl típusát
     * 4. Feltölti az eredeti képet
     * 5. Létrehoz egy miniatűr verziót
     * 6. Frissíti a termék adatait az új kép elérési utakkal
     * 
     * @param Product $product A termék, amelyhez a képet feltöltjük
     */
    private function handleImageUpload(Product $product): void
    {
        // Ellenőrizzük, hogy van-e feltöltött kép és nincs-e hiba
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            return;
        }

        // Beállítjuk a feltöltési könyvtárat
        // A public mappában kell lennie, hogy a böngésző elérhesse
        $uploadDir = __DIR__ . '/../../../public/uploads/products/';
        
        // Ha nem létezik a könyvtár, létrehozzuk
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Fájl kiterjesztés ellenőrzése
        $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        // Ha nem megfelelő a kiterjesztés, kilépünk
        if (!in_array($fileExtension, $allowedExtensions)) {
            return;
        }

        // Egyedi fájlnév generálása
        $fileName = uniqid('product_') . '.' . $fileExtension;
        $filePath = $uploadDir . $fileName;

        // Fájl feltöltése
        if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
            // Régi képek törlése, ha léteznek
            if ($product->getImagePath() && file_exists(__DIR__ . '/../../../public/' . $product->getImagePath())) {
                unlink(__DIR__ . '/../../../public/' . $product->getImagePath());
            }
            if ($product->getThumbnailPath() && file_exists(__DIR__ . '/../../../public/' . $product->getThumbnailPath())) {
                unlink(__DIR__ . '/../../../public/' . $product->getThumbnailPath());
            }

            // Miniatűr kép létrehozása
            $this->createThumbnail($filePath, $uploadDir . 'thumb_' . $fileName);

            // Termék adatainak frissítése az új kép elérési utakkal
            $product->setImagePath('uploads/products/' . $fileName);
            $product->setThumbnailPath('uploads/products/thumb_' . $fileName);
        }
    }

    /**
     * Miniatűr kép létrehozása
     * 
     * Ez a metódus létrehoz egy kisebb méretű verziót az eredeti képről.
     * A miniatűr kép használatos a termék listázásnál és a böngészésnél.
     * 
     * @param string $sourcePath Az eredeti kép elérési útja
     * @param string $targetPath A miniatűr kép mentési útvonala
     * @param int $width A miniatűr kép szélessége pixelben
     */
    private function createThumbnail(string $sourcePath, string $targetPath, int $width = 200): void
    {
        // Eredeti kép méreteinek lekérése
        list($srcWidth, $srcHeight, $type) = getimagesize($sourcePath);
        
        // Új méretek számítása az arányok megtartásával
        $ratio = $width / $srcWidth;
        $height = $srcHeight * $ratio;

        // Új kép létrehozása
        $thumb = imagecreatetruecolor($width, $height);

        // Forrás kép betöltése a megfelelő formátumban
        switch ($type) {
            case IMAGETYPE_JPEG:
                $source = imagecreatefromjpeg($sourcePath);
                break;
            case IMAGETYPE_PNG:
                $source = imagecreatefrompng($sourcePath);
                break;
            case IMAGETYPE_GIF:
                $source = imagecreatefromgif($sourcePath);
                break;
            default:
                return;
        }

        // Kép átméretezése
        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $width, $height, $srcWidth, $srcHeight);

        // Miniatűr kép mentése a megfelelő formátumban
        switch ($type) {
            case IMAGETYPE_JPEG:
                imagejpeg($thumb, $targetPath, 90); // 90 a minőség (0-100)
                break;
            case IMAGETYPE_PNG:
                imagepng($thumb, $targetPath, 9); // 9 a tömörítési szint (0-9)
                break;
            case IMAGETYPE_GIF:
                imagegif($thumb, $targetPath);
                break;
        }

        // Memória felszabadítása
        imagedestroy($source);
        imagedestroy($thumb);
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
