<?php

namespace Tests\Unit;

use Tests\TestCase;
use Webshop\Model\Product;
use Webshop\Model\Category;
use Webshop\Model\Order;
use Webshop\Model\User;
use Webshop\Model\Address;

/**
 * SimpleWebshopTest osztály - A webshop alapvető funkcióinak tesztelése
 * Ez az osztály a webshop alapvető működését teszteli, mint például:
 * - Adatbázis struktúra működése
 * - Rendelések létrehozása
 * 
 * Fontos tudnivalók:
 * - Az osztály a TestCase osztályból származik, ami alapvető tesztelési funkciókat biztosít
 * - Az entityManager egy adatbázis kezelő objektum, ami segít az adatok mentésében és lekérdezésében
 * - Minden teszt függetlenül fut, és a tesztesetek végén az adatbázis automatikusan visszaáll
 */
class SimpleWebshopTest extends TestCase
{
    /**
     * Teszteli az adatbázis struktúra működését
     * Ellenőrzi, hogy a kategóriák és termékek megfelelően mentődnek-e
     * 
     * A teszt lépései:
     * 1. Létrehoz egy kategóriát és ellenőrzi a mentését
     * 2. Létrehoz egy terméket a kategóriával és ellenőrzi a mentését
     * 3. Ellenőrzi a kapcsolatot a termék és a kategória között
     */
    public function test_database_structure()
    {
        // Kategória létrehozása és mentése
        // A persist() metódus jelzi az entityManager-nek, hogy ezt az objektumot mentse
        // A flush() metódus végrehajtja a tényleges mentést az adatbázisba
        $category = new Category();
        $category->setName('Teszt Kategória');
        
        $this->entityManager->persist($category);
        $this->entityManager->flush();
        
        // Ellenőrizzük, hogy a kategória sikeresen létrejött-e
        // Az assertNotNull ellenőrzi, hogy a kategória kapott-e egyedi azonosítót
        $this->assertNotNull($category->getId(), 'A kategória sikeresen létrejött az adatbázisban');

        // Termék létrehozása és mentése
        // A termék kapcsolódik a korábban létrehozott kategóriához
        $product = new Product();
        $product->setName('Teszt Termék');
        $product->setPrice(1000);
        $product->setStock(10);
        $product->setCategory($category);
        
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        
        // Ellenőrizzük, hogy a termék sikeresen létrejött-e
        // Az assertNotNull ellenőrzi, hogy a termék kapott-e egyedi azonosítót
        // Az assertEquals ellenőrzi, hogy a termék megfelelően kapcsolódik-e a kategóriához
        $this->assertNotNull($product->getId(), 'A termék sikeresen létrejött az adatbázisban');
        $this->assertEquals($category, $product->getCategory(), 'A termék kategóriája megfelelően be van állítva');
    }

    /**
     * Teszteli a rendelés létrehozásának folyamatát
     * Ellenőrzi, hogy egy teljes rendelés megfelelően mentődik-e
     * 
     * A teszt lépései:
     * 1. Létrehoz egy felhasználót
     * 2. Létrehoz egy címet a felhasználóhoz
     * 3. Létrehoz egy terméket
     * 4. Létrehoz egy rendelést a felhasználóval, címmel és termékkel
     * 5. Ellenőrzi, hogy minden adat megfelelően mentődött-e
     */
    public function test_can_place_order()
    {
        // Felhasználó létrehozása és mentése
        // A jelszó hash-elése biztonsági okokból történik
        $user = new User();
        $user->setName('Teszt Felhasználó');
        $user->setEmail('teszt@example.com');
        $user->setPassword(password_hash('jelszo123', PASSWORD_DEFAULT));
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Cím létrehozása és mentése
        // A cím kapcsolódik a felhasználóhoz
        $address = new Address();
        $address->setUser($user);
        $address->setName('Teszt Felhasználó');
        $address->setType('shipping');
        $address->setStreet('Teszt utca 1.');
        $address->setCity('Budapest');
        $address->setZipCode('1111');
        $address->setCountry('Magyarország');
        $this->entityManager->persist($address);
        $this->entityManager->flush();

        // Termék létrehozása és mentése
        $product = new Product();
        $product->setName('Teszt Termék');
        $product->setPrice(1000);
        $product->setStock(10);
        
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        
        // Rendelés létrehozása és mentése
        // A rendelés kapcsolódik a felhasználóhoz és a címhez
        $order = new Order();
        $order->setUser($user);
        $order->setStatus('új');
        $order->setTotalAmount(1000);
        $order->setPaymentMethod('készpénz');
        $order->setShippingMethod('házhozszállítás');
        $order->setBillingAddress($address);
        $order->setShippingAddress($address);
        
        $this->entityManager->persist($order);
        $this->entityManager->flush();
        
        // Ellenőrzések, hogy a rendelés megfelelően jött-e létre
        // Minden assertEquals ellenőrzi egy-egy tulajdonság helyességét
        $this->assertNotNull($order->getId(), 'A rendelés sikeresen létrejött');
        $this->assertEquals($user, $order->getUser(), 'A rendelés megfelelően kapcsolódik a felhasználóhoz');
        $this->assertEquals(1000, $order->getTotalAmount(), 'A rendelés végösszege helyes');
        $this->assertEquals('készpénz', $order->getPaymentMethod(), 'A fizetési mód megfelelően be van állítva');
        $this->assertEquals('házhozszállítás', $order->getShippingMethod(), 'A szállítási mód megfelelően be van állítva');
        $this->assertEquals($address, $order->getBillingAddress(), 'A számlázási cím megfelelően be van állítva');
        $this->assertEquals($address, $order->getShippingAddress(), 'A szállítási cím megfelelően be van állítva');
    }
} 