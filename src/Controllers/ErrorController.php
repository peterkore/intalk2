<?php

namespace Webshop\Controllers;

use Webshop\View;

class ErrorController extends BaseController
{
    public function index(): void
    {
        http_response_code(404);
        echo (new View())->render('404.php', [
            'title' => '404 - Oldal nem található',
            'message' => 'A keresett oldal nem található!'
        ]);
    }
}