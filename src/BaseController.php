<?php

namespace Webshop;

use Webshop\Controllers\ErrorController;


abstract class BaseController
{
    protected $entityManager;

    public function __construct()
    {
        $this->entityManager = EntityManagerFactory::getEntityManager();
    }

    //Protected láthatóságú metódus ajax kérés kezelésére, JSON response adással    
    protected function sendAjaxResponse($message = '', $statusCode = 200){
        // beállítjuk a tartalom típusát JSON formátumra
        header("Content-Type: application/json");
        //Beállítjuk az HTTP státuszkódot, például 200 (siker) vagy más hibakódot
        http_response_code($statusCode);
        //JSON formátummá alakítjuk a message-et
        echo json_encode(['message'=> $message]);
        //Kilépünk
        exit;
    }

    protected function handleError($message = ''): void
    {
        (new ErrorController($this->entityManager))->index($message);
    }

    protected function checkAdminAuth(): void
    {
        if (!isset($_SESSION['user']['is_admin'])) {
            header('Location: /login');
            exit;
        }
    }

    protected function checkCustomerAuth(): void
    {
        if (!isset($_SESSION['user']['loggedin_id'])) {
            header('Location: /login');
            exit;
        }
    }
}