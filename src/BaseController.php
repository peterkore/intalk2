<?php

namespace Webshop;

use Webshop\Controllers\ErrorController;


abstract class BaseController
{
    protected $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
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

    protected function handleError(): void
    {
        (new ErrorController($this->entityManager))->index();
    }
}