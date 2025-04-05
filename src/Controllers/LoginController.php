<?php

namespace Webshop\Controllers;

use Webshop\View;
use Webshop\Model\User;
use Webshop\BaseController;

class LoginController extends BaseController
{

    public function index(): void
    {

        if (isset($_SESSION['user']['loggedin_id'])) {
            if (isset($_SESSION['user']['is_admin'])) {
                header('Location: /admin/dashboard');
            } else {
                header('Location: /');
            }
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateToken();
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
                $_SESSION['user']['loggedin_id'] = $user->getId();
                    if ($user->isAdmin()) {
                        $_SESSION['user']['is_admin'] = true;
                        header('Location: /admin/dashboard');
                        exit;
                    } else {
                        header('Location: /');
                        exit;
                    }
            } else {
                 echo (new View())->render('login.php', [
                    'csrfToken' => $this->generateToken(),
                    'error' => 'Hibás email vagy jelszó!'
                ]);
            }
        } else {
            echo (new View())->render('login.php', [
                'csrfToken' => $this->generateToken()
            ]);
        }
    }
}
