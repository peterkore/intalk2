<?php

namespace Webshop\Controllers;

use Webshop\View;
use Webshop\BaseController;

class ErrorController extends BaseController
{
    public function index(string $message = ''): void
    {
        http_response_code(404);
        echo (new View())->render('404.php', [
            'title' => '404 - Oldal nem található',
            'message' => $message,
            'statusCode'=> 404
        ]);
    }
}