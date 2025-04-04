<?php
namespace Webshop\Controllers;

use Webshop\View;

use Webshop\BaseController;

class ContactController extends BaseController
{
    public function index()
    {
        $this->view('contact/index');
    }

    public function submit()
    {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $message = $_POST['message'] ?? '';

        if (empty($name) || empty($email) || empty($message)) {
            $_SESSION['contact_success'] = "Kérjük minden mezőt töltsön ki!";
            header("Location: /contact");
            exit;
        }

        // Email validalas
        $name = htmlspecialchars($name);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $message = htmlspecialchars($message);

        // Email tartalom
        $to = "test@webshop.com_invalid";
        $subject = "Új üzenet a feladótól: $name";
        $body = "Feladó: $name <$email>\n\n$message";

        if (mail($to, $subject, $body)) {
            $_SESSION['contact_success'] = "Köszönjük, hogy felvette velünk a kapcsolatot! Hamarosan válaszolunk.";
        } else {
            $_SESSION['contact_success'] = "Hiba történt az üzenet küldésekor. Kérjük, próbálja újra később.";
        }

        header("Location: /contact");
        exit;
    }
}