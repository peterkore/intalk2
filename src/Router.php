<?php

namespace Webshop;

use Webshop\Controllers\ErrorController;
use Webshop\Core\EntityManagerFactory;

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
        $segments = explode('/', trim($uri, '/'));
        $controller = ucfirst(!empty($segments[0]) ? $segments[0] : 'Index') . 'Controller';
        $method = $segments[1] ?? 'index';
        $params = array_slice($segments, 2);

        // Próbáljuk meg mindkét névtérben megtalálni a controller-t
        $controllerClass = "\\Webshop\\Controllers\\{$controller}";
        if (!class_exists($controllerClass)) {
            $controllerClass = "\\Webshop\\Controller\\{$controller}";
        }

        if (!class_exists($controllerClass)) {
            $this->handleError();
            return;
        }

        // Controller példányosítása az EntityManager-rel
        $instance = new $controllerClass($this->entityManager);

        try {
            $reflection = new \ReflectionMethod($controllerClass, $method);

            // A metódus paramétereinek számának lekérése
            $totalParams = $reflection->getNumberOfParameters();
            $requiredParams = $reflection->getNumberOfRequiredParameters();
            
            // Amennyiben kevesebb vagy több paramétert találunk az URL-ben adjunk 404-et.
            if (count($params) > $totalParams || count($params) < $requiredParams) {
                $this->handleError();
                return;
            } else {
                try {
                    $reflection->invokeArgs($instance, $params);
                } catch (\Throwable $th) {
                    echo $th->getMessage();
                }
               
            }
        } catch (\Throwable $th) {
            $this->handleError();
            return;
        }
    }

    private function handleError(): void
    {
        (new ErrorController($this->entityManager))->index();
    }
}
