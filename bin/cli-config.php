<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Webshop\Core\EntityManagerFactory;

require_once __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/Bootstrap.php';

$entityManager = EntityManagerFactory::getEntityManager();

return new SingleManagerProvider($entityManager); 