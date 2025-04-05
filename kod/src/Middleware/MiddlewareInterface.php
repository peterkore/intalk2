<?php

namespace Webshop\Middleware;

use Webshop\Core\Request;
use Webshop\Core\Response;

interface MiddlewareInterface
{
    public function handle(Request $request, callable $next): Response;
} 