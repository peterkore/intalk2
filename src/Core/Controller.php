<?php

namespace Webshop\Core;

abstract class Controller
{
    protected function render(string $view, array $data = []): Response
    {
        extract($data);
        
        ob_start();
        require __DIR__ . '/../View/' . $view . '.php';
        $content = ob_get_clean();
        
        return new Response($content);
    }
} 