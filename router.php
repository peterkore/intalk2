<?php

require_once __DIR__ . '/vendor/autoload.php';

use Webshop\Router;
use Webshop\Core\EntityManagerFactory;

// Session inicializálása
session_start();

// EntityManager létrehozása
$entityManager = EntityManagerFactory::getEntityManager();

// Router példány létrehozása
$router = new Router();

// URL lekérése
$uri = $_SERVER['REQUEST_URI'];

// Kérés továbbítása a routernek
$router->dispatch($uri); 