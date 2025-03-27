<?php

namespace Webshop\Controllers;

use Webshop\View;

class IndexController
{
    public function index()
    {
        echo (new View())->render('index.php', [
            'title' => 'Webshop index'
        ]);
    }
}