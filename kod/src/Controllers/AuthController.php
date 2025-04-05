<?php

namespace Webshop\Controllers;

use Webshop\View;
use Webshop\Model\User;
use Webshop\Core\Request;
use Webshop\Core\Response;
use Webshop\EntityManagerFactory;



class AuthController
{
    private $entityManager;

    public function __construct()
    {
        $this->entityManager = EntityManagerFactory::getEntityManager();
    }

    public function login()
    {
        if ($request->isPost()) {
            $email = $request->getPost('email');
            $password = $request->getPost('password');

            $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($user && password_verify($password, $user->getPassword())) {
                $request->getSession()->set('user', $user);
                return new Response('', 302, ['Location' => '/admin']);
            }

            echo (new View())->render('login.php', [
                'title' => 'Kezdőlap - Állatwebshop'
            ]);

            // return $this->render('login', [
            //     'error' => 'Érvénytelen email cím vagy jelszó'
            // ]);
        }

    }

    public function logout(Request $request): Response
    {
        $request->getSession()->remove('user');
        return new Response('', 302, ['Location' => '/login']);
    }
} 