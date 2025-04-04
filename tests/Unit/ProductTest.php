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
        // Kategória létrehozása és mentése az adatbázisba
        // A persist() metódus jelzi az entityManager-nek, hogy ezt az objektumot mentse
        // A flush() metódus végrehajtja a tényleges mentést az adatbázisba
        $category = new Category();
        $category->setName('Test kategória');
        $this->entityManager->persist($category);
        $this->entityManager->flush();

        // Új termék létrehozása és tulajdonságainak beállítása
        // Minden tulajdonság beállítása után a termék kapcsolódik a kategóriához
        $product = new Product();
        $product->setName('Test termék');
        $product->setDescription('Test leírás');
        $product->setPrice(1000);
        $product->setStock(10);
        $product->setCategory($category);

        // Termék mentése az adatbázisba
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        // Ellenőrzések, hogy a termék megfelelően jött-e létre
        // Az assertNotNull ellenőrzi, hogy a termék kapott-e egyedi azonosítót
        // A többi assertEquals ellenőrzi, hogy minden tulajdonság megfelelően mentődött-e
        $this->assertNotNull($product->getId());
        $this->assertEquals('Test termék', $product->getName());
        $this->assertEquals('Test leírás', $product->getDescription());
        $this->assertEquals(1000, $product->getPrice());
        $this->assertEquals(10, $product->getStock());
        $this->assertEquals($category, $product->getCategory());
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
        // Kategória létrehozása
        $category = new Category();
        $category->setName('Test kategória');
        $this->entityManager->persist($category);
        $this->entityManager->flush();

        // Termék létrehozása
        $product = new Product();
        $product->setName('Test termék');
        $product->setDescription('Test leírás');
        $product->setPrice(1000);
        $product->setStock(10);
        $product->setCategory($category);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        // Termék tulajdonságainak módosítása
        // A módosítások után nem kell újra persist()-et hívni,
        // mert a termék már "managed" állapotban van
        $product->setName('Frissített termék');
        $product->setPrice(2000);
        $product->setStock(5);

        // Módosítások mentése
        $this->entityManager->flush();

        // Ellenőrzések, hogy a módosítások megfelelően mentődtek-e
        $this->assertEquals('Frissített termék', $product->getName());
        $this->assertEquals(2000, $product->getPrice());
        $this->assertEquals(5, $product->getStock());
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
        // Kategória létrehozása
        $category = new Category();
        $category->setName('Test kategória');
        $this->entityManager->persist($category);
        $this->entityManager->flush();

        // Termék létrehozása
        $product = new Product();
        $product->setName('Test termék');
        $product->setDescription('Test leírás');
        $product->setPrice(1000);
        $product->setStock(10);
        $product->setCategory($category);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        // Termék törlése
        // Elmentjük az azonosítót, hogy később ellenőrizhessük a törlést
        $productId = $product->getId();
        $this->entityManager->remove($product);
        $this->entityManager->flush();

        // Ellenőrzés, hogy a termék tényleg törlődött-e
        // A find() metódus null-t ad vissza, ha nem találja a terméket
        $deletedProduct = $this->entityManager->getRepository(Product::class)->find($productId);
        $this->assertNull($deletedProduct);
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
        // Elvárás, hogy InvalidArgumentException kivétel keletkezzen
        // Ez a sor jelzi a teszt keretrendszernek, hogy a következő kód
        // InvalidArgumentException kivételt kell, hogy dobjon
        $this->expectException(\InvalidArgumentException::class);

        // Kategória létrehozása
        $category = new Category();
        $category->setName('Test kategória');
        $this->entityManager->persist($category);
        $this->entityManager->flush();

        // Termék létrehozása negatív készlettel
        // Ez a sor kivételt fog dobni, amit a teszt keretrendszer elkap
        $product = new Product();
        $product->setName('Test termék');
        $product->setDescription('Test leírás');
        $product->setPrice(1000);
        $product->setStock(-1); // Ez kivételt fog dobni
        $product->setCategory($category);

        // A mentés során kivétel keletkezik
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }
} 