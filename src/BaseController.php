<?php

namespace Webshop;


abstract class BaseController
{
    protected $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function sendAjaxResponse($message = '', $statusCode = 200){
        header("Content-Type: application/json");
        http_response_code($statusCode);
        echo json_encode(['message'=> $message]);
        exit;
    }
}
