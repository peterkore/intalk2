<?php

namespace Webshop\Middleware;

use Webshop\Core\Request;
use Webshop\Core\Response;
use Webshop\Model\User;

class AdminMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): Response
    {
        $user = $request->getSession()->get('user');
        
        if (!$user || !$user instanceof User || !$user->isAdmin()) {
            return new Response('Nincs jogosultságod az admin felület eléréséhez.', 403);
        }
        
        return $next($request);
    }
} 