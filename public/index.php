<?php
// Hibaüzenetek megjelenítése a fejlesztés idejére.
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session indítása 
session_start();

require_once __DIR__ . "/../vendor/autoload.php";

try {
    // Router példányosítása
    $router = new \Webshop\Router();

    // Request URI beolvasása
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Router vezénylése
    $router->dispatch($uri);
} catch (Exception $e) {
    // Hibakezelés
    echo "Error: " . $e->getMessage();
}
