<?php

namespace Webshop\Controllers;

use Webshop\BaseController;

class LogoutController extends BaseController
{
    public function index(): void
    {
        unset($_SESSION['user']);
        header('Location: /');
        exit;
    }
}