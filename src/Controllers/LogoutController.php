<?php

namespace Webshop\Controllers;

use Webshop\View;
use Webshop\Model\User;
use Webshop\BaseController;

class LogoutController extends BaseController
{
    public function index(): void
    {
        session_destroy();
        header('Location: /');
        exit;
    }
}