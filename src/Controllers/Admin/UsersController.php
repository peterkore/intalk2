<?php

namespace Webshop\Controllers\Admin;

use Webshop\View;
use Webshop\Model\User;
use Webshop\Model\Address;
use Webshop\BaseController;

class UsersController extends BaseController
{
    public function index(): void
    {
        $this->checkAdminAuth();

        echo (new View())->render('Admin/users.php', [
            'title' => 'Felhasználók - PetShop Admin',
            'users' => $this->entityManager->getRepository(User::class)->findAll(),

        ]);
    }

    public function view(int $userId): void
    {
        $this->checkAdminAuth();

        $user = $this->entityManager->getRepository(User::class)->find($userId);
        if (!$user) {
            header('Location: /admin/users');
            exit;
        }

        echo (new View())->render('Admin/user_view.php', [
            'title' => 'Felhasználó részletei - PetShop Admin',
            'user' => $user,
            'billingAddress' => $user->getDefaultAddress('billing'),
            'shippingAddress' => $user->getDefaultAddress('shipping')
        ]);
    }

    public function new(): void
    {
        $this->checkAdminAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = new User();
            $user->setName($_POST['name']);
            $user->setEmail($_POST['email']);
            $user->setPassword($_POST['password']);

            $billingAddress = new Address();
            $billingAddress->setType('billing');
            $billingAddress->setName('Számlázási cím');
            $billingAddress->setStreet($_POST['billing_street']);
            $billingAddress->setCity($_POST['billing_city']);
            $billingAddress->setZipCode($_POST['billing_postal_code']);
            $billingAddress->setCountry($_POST['billing_country']);
            $billingAddress->setPhone($_POST['billing_phone']);
            $billingAddress->setIsDefault(true);
            $billingAddress->setUser($user);
            $user->addAddress($billingAddress);

            $shippingAddress = new Address();
            $shippingAddress->setType('shipping');
            $shippingAddress->setName('Szállítási cím');
            $shippingAddress->setStreet($_POST['shipping_street']);
            $shippingAddress->setCity($_POST['shipping_city']);
            $shippingAddress->setZipCode($_POST['shipping_postal_code']);
            $shippingAddress->setCountry($_POST['shipping_country']);
            $shippingAddress->setPhone($_POST['shipping_phone']);
            $shippingAddress->setIsDefault(true);
            $billingAddress->setUser($user);
            $user->addAddress($shippingAddress);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            header('Location: /admin/users');
            exit;
        }

        echo (new View())->render('Admin/user_edit.php', [
            'title' => 'Új felhasználó - PetShop Admin',
            'user' => false
        ]);
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

        echo (new View())->render('Admin/user_edit.php', [
            'title' => 'Felhaszáló szerkesztése - PetShop Admin',
            'user' => $user
        ]);
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
