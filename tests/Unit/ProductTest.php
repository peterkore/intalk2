<?php

namespace Tests\Unit;

use Tests\TestCase;
use Webshop\Model\Product;
use Webshop\Model\Category;

/**
 * ProductTest osztály - A termékek kezelésével kapcsolatos tesztek
 * Ez az osztály a Product modell működését teszteli különböző esetekben
 * 
 * Fontos tudnivalók:
 * - Az osztály a TestCase osztályból származik, ami alapvető tesztelési funkciókat biztosít
 * - Az entityManager egy adatbázis kezelő objektum, ami segít az adatok mentésében és lekérdezésében
 * - Minden teszt függetlenül fut, és a tesztesetek végén az adatbázis automatikusan visszaáll
 */
class ProductTest extends TestCase
{
    /**
     * Teszteli, hogy létre lehet-e hozni egy új terméket
     * Ellenőrzi, hogy a termék minden tulajdonsága megfelelően beállítható-e
     * 
     * A teszt lépései:
     * 1. Létrehoz egy kategóriát
     * 2. Létrehoz egy új terméket a kategóriával
     * 3. Ellenőrzi, hogy minden adat megfelelően mentődött-e
     */
    public function test_can_create_product()
    {
        echo "\n\n=== Termék létrehozásának tesztelése ===\n";
        echo "1. Kategória létrehozása...\n";

        // Kategória létrehozása és mentése az adatbázisba
        $category = new Category();
        $category->setName('Test kategória');
        $this->entityManager->persist($category);
        $this->entityManager->flush();

        echo "✅ Kategória sikeresen létrehozva\n";
        echo "2. Termék létrehozása...\n";

        // Új termék létrehozása és tulajdonságainak beállítása
        $product = new Product();
        $product->setName('Test termék');
        $product->setDescription('Test leírás');
        $product->setPrice(1000);
        $product->setStock(10);
        $product->setCategory($category);

        echo "✅ Termék tulajdonságai beállítva\n";
        echo "3. Termék mentése az adatbázisba...\n";

        // Termék mentése az adatbázisba
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        echo "✅ Termék sikeresen mentve\n";
        echo "4. Ellenőrzések végrehajtása...\n";

        // Ellenőrzések
        $this->assertNotNull($product->getId(), 'A termék azonosítója nem lehet null, mert az adatbázisban létre kell jönnie');
        echo "✅ Azonosító ellenőrzése: OK\n";

        $this->assertEquals('Test termék', $product->getName(), 'A termék neve nem egyezik a beállított értékkel');
        echo "✅ Név ellenőrzése: OK\n";

        $this->assertEquals('Test leírás', $product->getDescription(), 'A termék leírása nem egyezik a beállított értékkel');
        echo "✅ Leírás ellenőrzése: OK\n";

        $this->assertEquals(1000, $product->getPrice(), 'A termék ára nem egyezik a beállított értékkel');
        echo "✅ Ár ellenőrzése: OK\n";

        $this->assertEquals(10, $product->getStock(), 'A termék készlete nem egyezik a beállított értékkel');
        echo "✅ Készlet ellenőrzése: OK\n";

        $this->assertEquals($category, $product->getCategory(), 'A termék kategóriája nem egyezik a beállított értékkel');
        echo "✅ Kategória ellenőrzése: OK\n";

        echo "=== Termék létrehozásának tesztelése befejezve ===\n\n";
    }

    /**
     * Teszteli, hogy frissíthető-e egy meglévő termék
     * Ellenőrzi, hogy a módosítások megfelelően mentődnek-e
     * 
     * A teszt lépései:
     * 1. Létrehoz egy terméket
     * 2. Módosítja a termék tulajdonságait
     * 3. Ellenőrzi, hogy a módosítások megfelelően mentődtek-e
     */
    public function test_can_update_product()
    {
        echo "\n\n=== Termék frissítésének tesztelése ===\n";
        echo "1. Kategória létrehozása...\n";

        // Kategória létrehozása
        $category = new Category();
        $category->setName('Test kategória');
        $this->entityManager->persist($category);
        $this->entityManager->flush();

        echo "✅ Kategória sikeresen létrehozva\n";
        echo "2. Termék létrehozása...\n";

        // Termék létrehozása
        $product = new Product();
        $product->setName('Test termék');
        $product->setDescription('Test leírás');
        $product->setPrice(1000);
        $product->setStock(10);
        $product->setCategory($category);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        echo "✅ Termék sikeresen létrehozva\n";
        echo "3. Termék tulajdonságainak módosítása...\n";

        // Termék tulajdonságainak módosítása
        $product->setName('Frissített termék');
        $product->setPrice(2000);
        $product->setStock(5);

        echo "✅ Tulajdonságok módosítva\n";
        echo "4. Módosítások mentése...\n";

        // Módosítások mentése
        $this->entityManager->flush();

        echo "✅ Módosítások sikeresen mentve\n";
        echo "5. Ellenőrzések végrehajtása...\n";

        // Ellenőrzések
        $this->assertEquals('Frissített termék', $product->getName(), 'A termék neve nem frissült megfelelően');
        echo "✅ Név frissítésének ellenőrzése: OK\n";

        $this->assertEquals(2000, $product->getPrice(), 'A termék ára nem frissült megfelelően');
        echo "✅ Ár frissítésének ellenőrzése: OK\n";

        $this->assertEquals(5, $product->getStock(), 'A termék készlete nem frissült megfelelően');
        echo "✅ Készlet frissítésének ellenőrzése: OK\n";

        echo "=== Termék frissítésének tesztelése befejezve ===\n\n";
    }

    /**
     * Teszteli, hogy törölhető-e egy termék
     * Ellenőrzi, hogy a törlés után a termék tényleg nem található-e az adatbázisban
     * 
     * A teszt lépései:
     * 1. Létrehoz egy terméket
     * 2. Elmenti az azonosítóját
     * 3. Törli a terméket
     * 4. Ellenőrzi, hogy a termék tényleg törlődött-e
     */
    public function test_can_delete_product()
    {
        echo "\n\n=== Termék törlésének tesztelése ===\n";
        echo "1. Kategória létrehozása...\n";

        // Kategória létrehozása
        $category = new Category();
        $category->setName('Test kategória');
        $this->entityManager->persist($category);
        $this->entityManager->flush();

        echo "✅ Kategória sikeresen létrehozva\n";
        echo "2. Termék létrehozása...\n";

        // Termék létrehozása
        $product = new Product();
        $product->setName('Test termék');
        $product->setDescription('Test leírás');
        $product->setPrice(1000);
        $product->setStock(10);
        $product->setCategory($category);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        echo "✅ Termék sikeresen létrehozva\n";
        echo "3. Termék törlése...\n";

        // Termék törlése
        $productId = $product->getId();
        $this->entityManager->remove($product);
        $this->entityManager->flush();

        echo "✅ Termék sikeresen törölve\n";
        echo "4. Ellenőrzés, hogy a termék tényleg törlődött-e...\n";

        // Ellenőrzés
        $deletedProduct = $this->entityManager->getRepository(Product::class)->find($productId);
        $this->assertNull($deletedProduct, 'A termék még mindig megtalálható az adatbázisban a törlés után');
        echo "✅ Törlés ellenőrzése: OK\n";

        echo "=== Termék törlésének tesztelése befejezve ===\n\n";
    }

    /**
     * Teszteli, hogy a készlet nem lehet negatív
     * Ellenőrzi, hogy negatív készlet esetén kivétel keletkezik-e
     * 
     * A teszt lépései:
     * 1. Beállítja, hogy InvalidArgumentException kivételt vár
     * 2. Megpróbál létrehozni egy terméket negatív készlettel
     * 3. A teszt sikeres, ha a kivétel tényleg keletkezik
     */
    public function test_product_stock_cannot_be_negative()
    {
        echo "\n\n=== Negatív készlet tesztelése ===\n";
        echo "1. Kategória létrehozása...\n";

        // Kategória létrehozása
        $category = new Category();
        $category->setName('Test kategória');
        $this->entityManager->persist($category);
        $this->entityManager->flush();

        echo "✅ Kategória sikeresen létrehozva\n";
        echo "2. Termék létrehozása negatív készlettel...\n";

        // Elvárás, hogy kivétel keletkezzen
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('A készlet nem lehet negatív');

        // Termék létrehozása negatív készlettel
        $product = new Product();
        $product->setName('Test termék');
        $product->setDescription('Test leírás');
        $product->setPrice(1000);
        $product->setStock(-1);
        $product->setCategory($category);

        echo "3. Termék mentése (kivétel várható)...\n";

        // A mentés során kivétel keletkezik
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        echo "✅ Kivétel keletkezett, ahogy vártuk\n";
        echo "=== Negatív készlet tesztelése befejezve ===\n\n";
    }
} 