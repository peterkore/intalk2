<?php
namespace Webshop\Controllers;

use Webshop\View;

use Webshop\BaseController;

class ContactController extends BaseController
{
    // A kontakt oldal megjelenítése
    public function index()
    {
        echo (new View())->render('contact.php', 
        [
            'title' => 'Kapcsolat',
        ]);
    }

    // Kontakt oldal űrlap beküldés feldolgozása
    public function submit()
    {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $message = $_POST['message'] ?? '';

        // Ellenőrizzük, hogy minden mező ki van e töltve
        if (empty($name) || empty($email) || empty($message)) 
        {
            // Hiányos kitöltés esetén hibaüzenetet jelenítünk meg
            echo (new View())->render('contact.php',
            [
                'title' => 'Kapcsolat',
                'message' => 'Kérjük minden mezőt töltsön ki!',
                'success' => false,
            ]);
        }

        // Email validalas
        $name = htmlspecialchars($name);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $message = htmlspecialchars($message);

        // Email tartalom
        $to = "test@webshop.com_invalid";
        $subject = "Új üzenet a feladótól: $name";
        $body = "Feladó: $name <$email>\n\n$message";

        // Email küldés
        $success = mail($to, $subject, $body);

        // Email küldést követően az oldal megjelenítése üzenet kiírásával 
        echo (new View())->render('contact.php', 
        [
            'title' => 'Kapcsolat',
            'message' => $success ? 'Köszönjük, hogy felvette velünk a kapcsolatot! Hamarosan válaszolunk.' : 'Hiba történt az üzenet küldésekor. Kérjük, próbálja újra később.',
            'success' => $success
        ]);
    }
}