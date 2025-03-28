<?php

namespace Webshop;

use Webshop\Controllers\ErrorController;
use Webshop\EntityManagerFactory;

class Router
{
    private $entityManager;

    public function __construct()
    {
        $this->entityManager = EntityManagerFactory::getEntityManager();
    }

    public function dispatch(string $uri): void
    {
        // URL elemeinek szétbontása
        // A megadott URI-t részekre bontjuk '/' karakter mentén, eltávolítva a végéről és elejéről a '/' karaktereket
        $segments = explode('/', trim($uri, '/'));
        // Beazonosítjuk a kontroller osztály nevét (és a kezdőbetűt nagybetűre cseréljük); ha nem létezik az adott controller osztály, az alapértelmezett 'IndexController'-t használjuk
        $controller = ucfirst(!empty($segments[0]) ? $segments[0] : 'Index') . 'Controller';
        // Beazonosítjuk az url-ben meghívott, kontroller osztályban található metódust; ha nem létezik a metódus, az alapértelmezett 'index'-et használjuk
        $method = $segments[1] ?? 'index';
        //Az url maradék részét paraméterekként kezeljük
        $params = array_slice($segments, 2);

        // Az aktuális kontroller osztály teljes nevét meghatározzuk a `\\Webshop\\Controllers` névtérben keresve
        $controllerClass = "\\Webshop\\Controllers\\{$controller}";

        //// Ha a kontroller osztály nem létezik a névtérben, hiba kezelést végzünk és kilépünk
        if (!class_exists($controllerClass)) {
            $this->handleError();
            return;
        }

        // Controller példányosítása az EntityManager-rel
        $instance = new $controllerClass($this->entityManager);

        try {
            // példányosítjuk a ReflectionMethod osztályt argumentumként átadva a kontrollerosztályt, illetve annak metódusát
            $reflection = new \ReflectionMethod($controllerClass, $method);

            // A metódus összes, illetve kötelező paramétereinek számának lekérése
            $totalParams = $reflection->getNumberOfParameters();
            $requiredParams = $reflection->getNumberOfRequiredParameters();
            
            // Ellenőrizzük, hogy a megadott paraméterek száma egyezik e a metódus paramétereinek számával
            if (count($params) > $totalParams || count($params) < $requiredParams) {
                // Amennyiben kevesebb vagy több paramétert találunk az URL-ben adjunk 404-et.
                $this->handleError();
                return;
            } else {
                try {
                    //Dinamikusan meghívja a kontrollerosztály metódusát átadva a szükséges paramétereket
                    $reflection->invokeArgs($instance, $params);
                    //Ha nem sikerül, hibakezelést hajt végre
                } catch (\Throwable $th) {
                    //Hibaüzenet kiírása
                    echo $th->getMessage();
                }
               
            }
        //Ha nem sikerül, hibakezelést hajt végre
        } catch (\Throwable $th) {
            //Meghívhja a handleError() metódust, ami lekezeli a hibát
            $this->handleError();
            return;
        }
    }

    private function handleError(): void
    {
        //példányosítja az ErrorControl osztályt argumentumként átadva az entityManager példányt és annak index metódusát.
        (new ErrorController($this->entityManager))->index();
    }
}