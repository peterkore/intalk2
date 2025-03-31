<?php

namespace Webshop\Controllers\Admin;

use Doctrine\ORM\EntityManager;
use Webshop\BaseController;
use Webshop\Model\User;
use Webshop\Model\Address;
use Webshop\EntityManagerFactory;

class UsersController extends BaseController
{
    public function __construct()
    {
        parent::__construct(EntityManagerFactory::getEntityManager());
    }

    public function index(): void
    {
        $this->checkAdminAuth();

        $users = $this->entityManager->getRepository(User::class)->findAll();
        require __DIR__ . '/../../Templates/Admin/users.php';
    }

    public function view(int $userId): void
    {
        $this->checkAdminAuth();

        $user = $this->entityManager->getRepository(User::class)->find($userId);
        if (!$user) {
            header('Location: /admin/users');
            exit;
        }

        $billingAddress = $user->getDefaultAddress('billing');
        $shippingAddress = $user->getDefaultAddress('shipping');

        require __DIR__ . '/../../Templates/Admin/user_view.php';
    }

    public function edit(int $userId): void
    {
        $this->checkAdminAuth();

        $user = $this->entityManager->getRepository(User::class)->find($userId);
        if (!$user) {
            header('Location: /admin/users');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user->setName($_POST['name']);
            $user->setEmail($_POST['email']);
            
            if (!empty($_POST['password'])) {
                $user->setPassword(password_hash($_POST['password'], PASSWORD_DEFAULT));
            }

            // Számlázási cím frissítése
            $billingAddress = $user->getDefaultAddress('billing') ?? new Address();
            $billingAddress->setType('billing');
            $billingAddress->setName('Számlázási cím');
            $billingAddress->setStreet($_POST['billing_street']);
            $billingAddress->setCity($_POST['billing_city']);
            $billingAddress->setZipCode($_POST['billing_postal_code']);
            $billingAddress->setCountry($_POST['billing_country']);
            $billingAddress->setPhone($_POST['billing_phone']);
            $billingAddress->setIsDefault(true);
            $user->addAddress($billingAddress);

            // Szállítási cím frissítése
            $shippingAddress = $user->getDefaultAddress('shipping') ?? new Address();
            $shippingAddress->setType('shipping');
            $shippingAddress->setName('Szállítási cím');
            $shippingAddress->setStreet($_POST['shipping_street']);
            $shippingAddress->setCity($_POST['shipping_city']);
            $shippingAddress->setZipCode($_POST['shipping_postal_code']);
            $shippingAddress->setCountry($_POST['shipping_country']);
            $shippingAddress->setPhone($_POST['shipping_phone']);
            $shippingAddress->setIsDefault(true);
            $user->addAddress($shippingAddress);

            $this->entityManager->flush();
            header('Location: /admin/users');
            exit;
        }

        require __DIR__ . '/../../Templates/Admin/user_edit.php';
    }

    public function delete(int $userId): void
    {
        $this->checkAdminAuth();

        $user = $this->entityManager->getRepository(User::class)->find($userId);
        if ($user) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
        }

        header('Location: /admin/users');
        exit;
    }
} 