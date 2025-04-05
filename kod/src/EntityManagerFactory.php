<?php

namespace Webshop;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Doctrine\DBAL\DriverManager;

class EntityManagerFactory
{
    private static ?EntityManager $entityManager = null;

    public static function getEntityManager(): EntityManager
    {
        if (self::$entityManager === null) {
            // Hozzunk létre egy egyszerű "alapértelmezett" Doctrine ORM konfigurációt
            $config = ORMSetup::createAttributeMetadataConfiguration(
                paths: [__DIR__ . '/Model'],
                isDevMode: true,
            );

            // Adatbázis konfiguráció
            $dbParams = [
                'driver' => $_ENV['DB_DRIVER'] ?? 'pdo_mysql',
                'host' => $_ENV['DB_HOST'] ?? 'localhost',
                'port' => $_ENV['DB_PORT'] ?? '3306',
                'user' => $_ENV['DB_USER'] ?? 'root',
                'password' => $_ENV['DB_PASSWORD'] ?? '',
                'dbname' => $_ENV['DB_NAME'] ?? 'webshop',
                'charset' => $_ENV['DB_CHARSET'] ?? 'utf8mb4'
            ];

            try {
                $connection = DriverManager::getConnection(array_merge($dbParams), $config);

                // Ellenőrzés: Megpróbálunk egyszerű lekérdezést végrehajtani
                $connection->executeQuery('SELECT 1');
            } catch (\Exception $e) {
                try {
                    if (strpos($e->getMessage(), "Unknown database 'webshop'") !== false) {
                        unset($dbParams['dbname']);
                        $dbName = $_ENV['DB_NAME'] ?? 'webshop';
                        $connection = DriverManager::getConnection(array_merge($dbParams), $config);
                        $connection->executeStatement("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                        $connection->close();
                    }
                } catch (\Exception $e) {
                    throw new \RuntimeException($e->getMessage());
                }
                // Ha hiba történik, kivételt dobunk
                throw new \RuntimeException($e->getMessage());
            };
            $connection = DriverManager::getConnection($dbParams, $config);
            self::$entityManager = new EntityManager($connection, $config);
        }

        return self::$entityManager;
    }

    public static function getConsoleRunner(): ConsoleRunner
    {
        return new ConsoleRunner(
            new SingleManagerProvider(self::getEntityManager())
        );
    }
}
