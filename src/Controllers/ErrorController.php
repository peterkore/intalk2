<?php

namespace Webshop\Controllers;

use Webshop\View;

class ErrorController
{
    public function index()
    {
        echo (new View())->render('404.php', [
            'message' => '404 A keresett lap nem található!',
            'title' => '404',
            'statusCode'=> 404
        ]);
    }
}