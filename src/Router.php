<?php

namespace Webshop;

use Webshop\Controllers\ErrorController;

class Router
{
    public function dispatch(string $uri): void
    {
        // URL elemeinek szétbontása
        // A megadott URI-t részekre bontjuk '/' karakter mentén, eltávolítva a végéről és elejéről a '/' karaktereket
        $segments = explode('/', trim($uri, '/'));
        
        if ($segments[0] == ''){
            $segments[0] = 'Index';
        }
        // Lekezeljük a több szintű könyvtárstruktúrát a Controllers könyvtár alatt, ha van
        // $path változóba eltároljuk a controller osztályok alap elérési útját
        $path = __DIR__ . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR;
        // $prefix változóba eltároljuk controller osztályok alap névterét
        $prefix = '\\Webshop\\Controllers\\';

        // Amíg a controllers könyvár alatt létezik a segments[0]-ban megadott könyvtár (nagybetűvel kezdve)
        while(!empty($segments[0]) && is_dir($path . ucfirst($segments[0]))){
            // A $currentDir változóba eltesszük az aktuális $segments[0] értékét, amit egyben törlünk a $segments tömbből
            $currentDir = ucfirst(array_shift($segments));
            // A $path változó értékéhez hozzáfűzzük a könyvtár nevét és a megfelelő separatort
            $path .= $currentDir . DIRECTORY_SEPARATOR;
            //  A $prefix változó értékéhez hozzáfűzzük a könyvtár nevét és a megfelelő separatort
            $prefix .= $currentDir . '\\';
        }

        // Beazonosítjuk a kontroller osztály nevét (és a kezdőbetűt nagybetűre cseréljük); ha nem létezik az adott controller osztály, az alapértelmezett 'IndexController'-t használjuk
        $controller = ucfirst(!empty($segments[0]) ? $segments[0] : 'Index') . 'Controller';
        // Beazonosítjuk az url-ben meghívott, kontroller osztályban található metódust; ha nem létezik a metódus, az alapértelmezett 'index'-et használjuk
        $method = $segments[1] ?? 'index';
        //Az url maradék részét paraméterekként kezeljük
        $params = array_slice($segments, 2);

        // Az aktuális kontroller osztály teljes nevét meghatározzuk a `\\Webshop\\Controllers` névtérben keresve
        $controllerClass = $prefix . $controller;

        // Ha a kontroller osztály nem létezik a névtérben, hiba kezelést végzünk és kilépünk
        if (!class_exists($controllerClass)) {
            $this->handleError('A ' . $controllerClass . ' osztály nem található');
            // @TODO-extra megpróbáljuk példányosítani az eredeti alap osztályt
            return;
        }

        // Controller osztály példányosítása
        $instance = new $controllerClass();

        try {
            // példányosítjuk a ReflectionMethod osztályt argumentumként átadva a kontrollerosztályt, illetve annak metódusát
            $reflection = new \ReflectionMethod($controllerClass, $method);

            // A metódus összes, illetve kötelező paramétereinek számának lekérése
            $totalParams = $reflection->getNumberOfParameters();
            $requiredParams = $reflection->getNumberOfRequiredParameters();
            
            // Ellenőrizzük, hogy a megadott paraméterek száma egyezik e a metódus paramétereinek számával
            if (count($params) > $totalParams || count($params) < $requiredParams) {
                // Amennyiben kevesebb vagy több paramétert találunk az URL-ben adjunk 404-et.
                $this->handleError('Túl sok vagy túl kevés paraméter!');
                return;
            } else {
                try {
                    // Dinamikusan meghívja a kontrollerosztály metódusát átadva a szükséges paramétereket
                    $reflection->invokeArgs($instance, $params);
                    // Ha nem sikerül, hibakezelést hajt végre
                } catch (\Throwable $th) {
                    // Hibaüzenet kiírása
                    echo $th->getMessage();
                }
               
            }
        // Ha nem sikerül, hibakezelést hajt végre
        } catch (\Throwable $th) {
            //Meghívhja a handleError() metódust, ami lekezeli a hibát
            $this->handleError($controllerClass . ' osztály ' . $method . ' metódusa nem található');
            return;
        }
    }

    private function handleError(string $message = ''): void
    {
        // Példányosítja az ErrorController osztályt és meghívja annak index metódusát.
        (new ErrorController())->index($message);
    }
}