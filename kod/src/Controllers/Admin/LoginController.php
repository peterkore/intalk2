<?php

namespace Webshop\Controllers\Admin;

use Webshop\View;
use Webshop\Model\User;
use Webshop\BaseController;

class LoginController extends BaseController
{

    public function index(): void
    {
        // Debug információk
        error_log('Login metódus meghívva');
        error_log('Session állapot: ' . print_r($_SESSION, true));
        error_log('POST adatok: ' . print_r($_POST, true));

        if (isset($_SESSION['admin_id'])) {
            error_log('Már be van jelentkezve, átirányítás a dashboard-ra');
            header('Location: /admin/dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            error_log('Bejelentkezési kísérlet: ' . $email);

            $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($user) {
                error_log('Felhasználó található: ' . $user->getId());
                error_log('Jelszó ellenőrzés: ' . ($password === $user->getPassword() ? 'Sikeres' : 'Sikertelen'));
            } else {
                error_log('Felhasználó nem található');
            }

            if ($user && password_verify($password, $user->getPassword())) {
                $_SESSION['admin_id'] = $user->getId();
                $_SESSION['admin_email'] = $user->getEmail();
                error_log('Sikeres bejelentkezés, átirányítás a dashboard-ra');
                header('Location: /admin/dashboard');
                exit;
            } else {
                error_log('Sikertelen bejelentkezés');
                echo (new View())->render('Admin/login.php', [
                    'error' => 'Hibás email vagy jelszó!'
                ]);
            }
        } else {
            error_log('Login űrlap megjelenítése');
            echo (new View())->render('Admin/login.php');
        }
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: /admin/login');
        exit;
    }
}