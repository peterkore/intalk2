<?php

namespace Tests\Unit;

use Tests\TestCase;
use Webshop\Model\Product;
use Webshop\Model\Category;
use Webshop\Model\Order;
use Webshop\Model\OrderItem;
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
     * Ellenőrzi, hogy a kapcsolatok megfelelően működnek-e
     */
    public function test_database_structure()
    {
        echo "\n\n=== Adatbázis struktúra tesztelése ===\n";
        echo "1. Felhasználó létrehozása...\n";

        // Felhasználó létrehozása
        $user = new User();
        $user->setName('Test Vásárló');
        $user->setEmail('test@example.com');
        $user->setPassword('test123');
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        echo "✅ Felhasználó sikeresen létrehozva\n";
        echo "2. Cím létrehozása...\n";

        // Cím létrehozása
        $address = new Address();
        $address->setUser($user);
        $address->setType('billing');
        $address->setName('Teszt Cím');
        $address->setStreet('Test utca 1');
        $address->setCity('Test város');
        $address->setZipCode('1234');
        $address->setCountry('Magyarország');
        $this->entityManager->persist($address);
        $this->entityManager->flush();

        echo "✅ Cím sikeresen létrehozva\n";
        echo "3. Kategória létrehozása...\n";

        // Kategória létrehozása
        $category = new Category();
        $category->setName('Test kategória');
        $this->entityManager->persist($category);
        $this->entityManager->flush();

        echo "✅ Kategória sikeresen létrehozva\n";
        echo "4. Termék létrehozása...\n";

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
        echo "5. Rendelés létrehozása...\n";

        // Rendelés létrehozása
        $order = new Order();
        $order->setUser($user);
        $order->setStatus(Order::STATUS_PENDING);
        $order->setPaymentMethod(Order::PAYMENT_METHOD_CASH);
        $order->setShippingMethod(Order::SHIPPING_METHOD_STANDARD);
        $order->setBillingAddress($address);
        $order->setShippingAddress($address);
        $order->setTotalAmount(1000);

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        echo "✅ Rendelés sikeresen létrehozva\n";
        echo "6. Rendelési tétel létrehozása...\n";

        // Rendelési tétel létrehozása
        $orderItem = new OrderItem();
        $orderItem->setOrder($order);
        $orderItem->setProduct($product);
        $orderItem->setQuantity(2);
        $orderItem->setPrice(1000);

        $this->entityManager->persist($orderItem);
        $this->entityManager->flush();

        echo "✅ Rendelési tétel sikeresen létrehozva\n";
        echo "7. Ellenőrzések végrehajtása...\n";

        // Ellenőrzések
        $this->assertNotNull($user->getId(), 'A felhasználó azonosítója nem lehet null');
        echo "✅ Felhasználó azonosító ellenőrzése: OK\n";

        $this->assertNotNull($address->getId(), 'A cím azonosítója nem lehet null');
        echo "✅ Cím azonosító ellenőrzése: OK\n";

        $this->assertNotNull($category->getId(), 'A kategória azonosítója nem lehet null');
        echo "✅ Kategória azonosító ellenőrzése: OK\n";

        $this->assertNotNull($product->getId(), 'A termék azonosítója nem lehet null');
        echo "✅ Termék azonosító ellenőrzése: OK\n";

        $this->assertNotNull($order->getId(), 'A rendelés azonosítója nem lehet null');
        echo "✅ Rendelés azonosító ellenőrzése: OK\n";

        $this->assertNotNull($orderItem->getId(), 'A rendelési tétel azonosítója nem lehet null');
        echo "✅ Rendelési tétel azonosító ellenőrzése: OK\n";

        $this->assertEquals($user, $order->getUser(), 'A rendelés felhasználója nem egyezik');
        echo "✅ Rendelés-felhasználó kapcsolat ellenőrzése: OK\n";

        $this->assertEquals($address, $order->getBillingAddress(), 'A rendelés számlázási címe nem egyezik');
        echo "✅ Rendelés-számlázási cím kapcsolat ellenőrzése: OK\n";

        $this->assertEquals($address, $order->getShippingAddress(), 'A rendelés szállítási címe nem egyezik');
        echo "✅ Rendelés-szállítási cím kapcsolat ellenőrzése: OK\n";

        $this->assertEquals($order, $orderItem->getOrder(), 'A rendelési tétel rendelése nem egyezik');
        echo "✅ Rendelési tétel-rendelés kapcsolat ellenőrzése: OK\n";

        $this->assertEquals($product, $orderItem->getProduct(), 'A rendelési tétel terméke nem egyezik');
        echo "✅ Rendelési tétel-termék kapcsolat ellenőrzése: OK\n";

        echo "=== Adatbázis struktúra tesztelése befejezve ===\n\n";
    }

    /**
     * Teszteli a rendelések létrehozását
     * Ellenőrzi, hogy a rendelések megfelelően jönnek-e létre
     */
    public function test_order_creation()
    {
        echo "\n\n=== Rendelés létrehozásának tesztelése ===\n";
        echo "1. Felhasználó létrehozása...\n";

        // Felhasználó létrehozása
        $user = new User();
        $user->setName('Test Vásárló');
        $user->setEmail('test@example.com');
        $user->setPassword('test123');
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        echo "✅ Felhasználó sikeresen létrehozva\n";
        echo "2. Cím létrehozása...\n";

        // Cím létrehozása
        $address = new Address();
        $address->setUser($user);
        $address->setType('billing');
        $address->setName('Teszt Cím');
        $address->setStreet('Test utca 1');
        $address->setCity('Test város');
        $address->setZipCode('1234');
        $address->setCountry('Magyarország');
        $this->entityManager->persist($address);
        $this->entityManager->flush();

        echo "✅ Cím sikeresen létrehozva\n";
        echo "3. Kategória létrehozása...\n";

        // Kategória létrehozása
        $category = new Category();
        $category->setName('Test kategória');
        $this->entityManager->persist($category);
        $this->entityManager->flush();

        echo "✅ Kategória sikeresen létrehozva\n";
        echo "4. Termék létrehozása...\n";

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
        echo "5. Rendelés létrehozása...\n";

        // Rendelés létrehozása
        $order = new Order();
        $order->setUser($user);
        $order->setStatus(Order::STATUS_PENDING);
        $order->setPaymentMethod(Order::PAYMENT_METHOD_CASH);
        $order->setShippingMethod(Order::SHIPPING_METHOD_STANDARD);
        $order->setBillingAddress($address);
        $order->setShippingAddress($address);
        $order->setTotalAmount(1000);

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        echo "✅ Rendelés sikeresen létrehozva\n";
        echo "6. Rendelési tétel létrehozása...\n";

        // Rendelési tétel létrehozása
        $orderItem = new OrderItem();
        $orderItem->setOrder($order);
        $orderItem->setProduct($product);
        $orderItem->setQuantity(2);
        $orderItem->setPrice(1000);

        $this->entityManager->persist($orderItem);
        $this->entityManager->flush();

        echo "✅ Rendelési tétel sikeresen létrehozva\n";
        echo "7. Ellenőrzések végrehajtása...\n";

        // Ellenőrzések
        $this->assertEquals('Test Vásárló', $user->getName(), 'A felhasználó neve nem egyezik');
        echo "✅ Felhasználó neve ellenőrzése: OK\n";

        $this->assertEquals('test@example.com', $user->getEmail(), 'A felhasználó email címe nem egyezik');
        echo "✅ Felhasználó email címe ellenőrzése: OK\n";

        $this->assertEquals(Order::STATUS_PENDING, $order->getStatus(), 'A rendelés státusza nem egyezik');
        echo "✅ Rendelés státusza ellenőrzése: OK\n";

        $this->assertEquals(Order::PAYMENT_METHOD_CASH, $order->getPaymentMethod(), 'A rendelés fizetési módja nem egyezik');
        echo "✅ Rendelés fizetési módja ellenőrzése: OK\n";

        $this->assertEquals(Order::SHIPPING_METHOD_STANDARD, $order->getShippingMethod(), 'A rendelés szállítási módja nem egyezik');
        echo "✅ Rendelés szállítási módja ellenőrzése: OK\n";

        $this->assertEquals(2, $orderItem->getQuantity(), 'A rendelési tétel mennyisége nem egyezik');
        echo "✅ Rendelési tétel mennyisége ellenőrzése: OK\n";

        $this->assertEquals(1000, $orderItem->getPrice(), 'A rendelési tétel ára nem egyezik');
        echo "✅ Rendelési tétel ára ellenőrzése: OK\n";

        echo "=== Rendelés létrehozásának tesztelése befejezve ===\n\n";
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
        $address->setType('billing');
        $address->setName('Teszt Cím');
        $address->setStreet('Test utca 1');
        $address->setCity('Test város');
        $address->setZipCode('1234');
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