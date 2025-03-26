<?php

namespace Webshop\Core;

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
            $paths = [__DIR__ . '/../Model'];
            $isDevMode = true;

            // Adatbázis konfiguráció
            $dbParams = [
                'driver'   => 'pdo_mysql',
                'host'     => $_ENV['DB_HOST'],
                'user'     => $_ENV['DB_USER'],
                'password' => $_ENV['DB_PASSWORD'],
                'dbname'   => $_ENV['DB_NAME'],
                'charset'  => $_ENV['DB_CHARSET']
            ];

            $config = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);
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