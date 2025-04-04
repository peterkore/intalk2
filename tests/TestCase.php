<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class TestCase extends BaseTestCase
{
    protected $entityManager;

    protected function setUp(): void
    {
        parent::setUp();
        
        // In-memory SQLite adatbázis létrehozása
        $paths = [__DIR__ . '/../src/Model'];
        $isDevMode = true;
        
        $dbParams = [
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ];
        
        $config = Setup::createConfiguration($isDevMode);
        $config->setMetadataDriverImpl(
            new \Doctrine\ORM\Mapping\Driver\AttributeDriver($paths)
        );
        
        $this->entityManager = EntityManager::create($dbParams, $config);
        
        // Séma létrehozása
        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        
        // Séma törlése
        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($metadata);
    }
} 