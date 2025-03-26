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
    // Először csatlakozunk a MySQL szerverhez adatbázis nélkül
    $tmpConnection = DriverManager::getConnection([
        'driver' => 'pdo_mysql',
        'host' => $_ENV['DB_HOST'] ?? 'localhost',
        'port' => $_ENV['DB_PORT'] ?? '3306',
        'user' => $_ENV['DB_USER'] ?? 'root',
        'password' => $_ENV['DB_PASSWORD'] ?? '',
        'charset' => $_ENV['DB_CHARSET'] ?? 'utf8mb4',
    ], $config);

    // Létrehozzuk az adatbázist, ha nem létezik
    $dbName = $_ENV['DB_NAME'] ?? 'webshop';
    $tmpConnection->executeStatement("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $tmpConnection->close();

    // Most már csatlakozhatunk az adatbázishoz
    $connection = DriverManager::getConnection([
        'driver' => 'pdo_mysql',
        'host' => $_ENV['DB_HOST'] ?? 'localhost',
        'port' => $_ENV['DB_PORT'] ?? '3306',
        'dbname' => $dbName,
        'user' => $_ENV['DB_USER'] ?? 'root',
        'password' => $_ENV['DB_PASSWORD'] ?? '',
        'charset' => $_ENV['DB_CHARSET'] ?? 'utf8mb4',
    ], $config);

    // Ellenőrzés: Megpróbálunk egyszerű lekérdezést végrehajtani
    $connection->executeQuery('SELECT 1');

    // Az entitymanager példányosítása
    $entityManager = new EntityManager($connection, $config);

} catch (\Exception $e) {
    // Ha bármilyen hiba történik, kivételt dobunk
    throw new \RuntimeException('Nem sikerült csatlakozni az adatbázishoz: ' . $e->getMessage());
}

return $entityManager;