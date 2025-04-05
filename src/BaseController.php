<?php

namespace Webshop;

use Webshop\Controllers\ErrorController;


abstract class BaseController
{
    protected $entityManager;

    public function __construct()
    {
        $this->entityManager = EntityManagerFactory::getEntityManager();
    }

    // Protected láthatóságú metódus ajax kérés kezelésére, JSON response adással    
    protected function sendAjaxResponse($message = '', $statusCode = 200){
        // beállítjuk a tartalom típusát JSON formátumra
        header("Content-Type: application/json");
        //Beállítjuk az HTTP státuszkódot, például 200 (siker) vagy más hibakódot
        http_response_code($statusCode);
        //JSON formátummá alakítjuk a message-et
        echo json_encode(['message'=> $message]);
        //Kilépünk
        exit;
    }

    // Hibakezelés
    protected function handleError(int $statusCode = 404, string $message = ''): void
    {
        (new ErrorController($this->entityManager))->index($statusCode, $message);
        exit;
    }

    // Admin jogosultság ellenőrzése
    protected function checkAdminAuth(): void
    {
        if (!isset($_SESSION['user']['is_admin'])) {
            header('Location: /login');
            exit;
        }
    }

    // Customer jogosultság ellenőrzése
    protected function checkUserIsLoggedin(): int
    {
        if (!isset($_SESSION['user']['loggedin_id'])) {
            header('Location: /login');
            exit;
        }
        return $_SESSION['user']['loggedin_id'];
    }

    // Token generálása
    protected function generateToken(): string {
        $token = hash_hmac('sha256', bin2hex(random_bytes(16)), 'egyedi_salt');
        $_SESSION['csrf_token'][$token] = time();
        return $token;
    }

    // Token ellenőrzése
    protected function validateToken(): void {
        $this->removeExpiredTokens();
        if(isset($_SESSION['csrf_token'][$_POST['csrf_token']]) ){
            unset($_SESSION['csrf_token'][$_POST['csrf_token']]);
            unset($_POST['csrf_token']);
        }else{
            $this->handleError(500, 'A kérés nem hitelesíthető!');
        }    
    }

    // Lejárt tokenek törlése
    private function removeExpiredTokens(): void {
        $tokenExpirationTime = isset($_ENV['CSRF_TOKEN_EXPIRATION_TIME']) ? (int)$_ENV['CSRF_TOKEN_EXPIRATION_TIME'] : 3600;
        $currentTime = time();
        foreach ($_SESSION['csrf_token'] as $token => $timestamp) {
            if ($currentTime - $timestamp > $tokenExpirationTime) {
                unset($_SESSION['csrf_token'][$token]);
            }
        }
    }
}