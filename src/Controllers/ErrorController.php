<?php

namespace Webshop\Controllers;

use Webshop\View;

class ErrorController
{
    public function index(int $statusCode = 404, string $message = ''): void
    {
        http_response_code($statusCode);
        echo (new View())->render('error.php', [
            'title' => $statusCode . ' - ' . $this->getDescription($statusCode),
            'message' => $message,
            'statusCode' => $statusCode
        ]);
    }

    // Státusz kód alapján visszadja a szöveges üzenetet
    private function getDescription($statusCode)
    {
        $statusDescriptions = [
            400 => 'Hibás kérés - Érvénytelen kérés',
            401 => 'Nem hitelesített - Bejelentkezés szükséges',
            403 => 'Tiltott - A hozzáférés megtagadva',
            404 => 'Oldal nem található',
            500 => 'Belső szerverhiba',
        ];

        return $statusDescriptions[$statusCode] ?? 'Ismeretlen státuszkód';
    }
}
