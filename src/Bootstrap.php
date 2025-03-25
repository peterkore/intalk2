<?php

use Dotenv\Dotenv;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;

// .env fájl használata
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

// Hozzunk létre egy egyszerű "alapértelmezett" Doctrine ORM konfigurációt
$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: [__DIR__ . '/Model'],
    isDevMode: true,
);

try {
    $connection = DriverManager::getConnection([
        'driver' => 'pdo_mysql',
        'host' => $_ENV['DB_HOST'] ?? 'localhost',
        'dbname' => $_ENV['DB_NAME'] ?? 'webshop',
        'user' => $_ENV['DB_USER'] ?? 'root',
        'password' => $_ENV['DB_PASSWORD'] ?? '',
        'charset' => $_ENV['DB_CHARSET'] ?? 'utf8mb4',
    ], $config);

    // Ellenőrzés: Megpróbálunk egyszerű lekérdezést végrehajtani
    $connection->executeQuery('SELECT 1');

} catch (\Exception $e) {
    // Ha bármilyen hiba történik, kivételt dobunk
    throw new \RuntimeException('Nem sikerült csatlakozni az adatbázishoz');
}

// Az entitymanager példányosítása
$entityManager = new EntityManager($connection, $config);