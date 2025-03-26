<?php

namespace Webshop\Controller;

use Webshop\Core\Controller;
use Webshop\Core\Request;
use Webshop\Core\Response;
use Webshop\Model\User;
use Webshop\Core\EntityManagerFactory;

class AuthController extends Controller
{
    private $entityManager;

    public function __construct()
    {
        $this->entityManager = EntityManagerFactory::getEntityManager();
    }

    public function login(Request $request): Response
    {
        if ($request->isPost()) {
            $email = $request->getPost('email');
            $password = $request->getPost('password');

            $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($user && password_verify($password, $user->getPassword())) {
                $request->getSession()->set('user', $user);
                return new Response('', 302, ['Location' => '/admin']);
            }

            return $this->render('login', [
                'error' => 'Érvénytelen email cím vagy jelszó'
            ]);
        }

        return $this->render('login');
    }

    public function logout(Request $request): Response
    {
        $request->getSession()->remove('user');
        return new Response('', 302, ['Location' => '/login']);
    }
} 