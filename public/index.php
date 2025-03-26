<?php
// Hibaüzenetek megjelenítése a fejlesztés idejére.
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Webshop\Router;
use Webshop\Core\EntityManagerFactory;

// Környezeti változók betöltése
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Entity Manager inicializálása
$entityManager = EntityManagerFactory::getEntityManager();

// Router inicializálása
$router = new Router();

// Kérés feldolgozása
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

try {
    $router->dispatch($uri);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
