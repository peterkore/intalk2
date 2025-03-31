<?php

namespace Webshop\Controllers\Admin;

use Doctrine\ORM\EntityManager;
use Webshop\BaseController;
use Webshop\Model\Category;
use Webshop\EntityManagerFactory;

class CategoriesController extends BaseController
{
    public function __construct()
    {
        parent::__construct(EntityManagerFactory::getEntityManager());
    }

    public function index(): void
    {
        $this->checkAdminAuth();

        $categories = $this->entityManager->getRepository(Category::class)->findAll();
        require __DIR__ . '/../../Templates/Admin/categories.php';
    }

    public function view(int $categoryId): void
    {
        $this->checkAdminAuth();

        $category = $this->entityManager->getRepository(Category::class)->find($categoryId);
        if (!$category) {
            header('Location: /admin/categories');
            exit;
        }

        require __DIR__ . '/../../Templates/Admin/category_view.php';
    }

    public function edit(int $categoryId): void
    {
        $this->checkAdminAuth();

        $category = $this->entityManager->getRepository(Category::class)->find($categoryId);
        if (!$category) {
            header('Location: /admin/categories');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category->setName($_POST['name']);
            $category->setDescription($_POST['description']);

            $this->entityManager->flush();
            header('Location: /admin/categories');
            exit;
        }

        require __DIR__ . '/../../Templates/Admin/category_edit.php';
    }

    public function create(): void
    {
        $this->checkAdminAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category = new Category();
            $category->setName($_POST['name']);
            $category->setDescription($_POST['description']);

            $this->entityManager->persist($category);
            $this->entityManager->flush();
            header('Location: /admin/categories');
            exit;
        }

        require __DIR__ . '/../../Templates/Admin/category_create.php';
    }

    public function delete(int $categoryId): void
    {
        $this->checkAdminAuth();

        $category = $this->entityManager->getRepository(Category::class)->find($categoryId);
        if ($category) {
            $this->entityManager->remove($category);
            $this->entityManager->flush();
        }

        header('Location: /admin/categories');
        exit;
    }
} 