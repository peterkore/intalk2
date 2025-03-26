<?php

namespace Webshop\Core;

use Webshop\Controllers\ErrorController;

class Router
{
    public function dispatch(string $uri): void
    {
        // URL elemeinek szétbontása
        $segments = explode('/', trim($uri, '/'));
        $controller = ucfirst(!empty($segments[0]) ? $segments[0] : 'Index') . 'Controller';
        $method = $segments[1] ?? 'index';
        $params = array_slice($segments, 2);

        $controllerClass = "\\Webshop\\Controllers\\{$controller}";

        if (!class_exists($controllerClass)) {
            $this->handleError();
            return;
        }

        // EntityManager létrehozása az EntityManagerFactory segítségével
        $entityManager = EntityManagerFactory::getEntityManager();
        $instance = new $controllerClass($entityManager);

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
                $reflection->invokeArgs($instance, $params);
            }
        } catch (\Throwable $th) {
            $this->handleError();
            return;
        }
    }

    private function handleError(): void
    {
        (new ErrorController)->index();
    }
} 