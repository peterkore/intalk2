<?php

namespace Webshop\Controllers\Admin;

use Webshop\View;
use Webshop\BaseController;
use Webshop\Model\Category;

class CategoriesController extends BaseController
{
    public function index(): void
    {
        $this->checkAdminAuth();

        $categories = $this->entityManager->getRepository(Category::class)->findAll();
        
        echo (new View())->render('Admin/categories.php', [
            'title' => 'Kategóriák - PetShop Admin',
            'categories' => $this->entityManager->getRepository(Category::class)->findAll(),

        ]);
    }

    public function view(int $categoryId): void
    {
        $this->checkAdminAuth();

        $category = $this->entityManager->getRepository(Category::class)->find($categoryId);
        if (!$category) {
            header('Location: /admin/categories');
            exit;
        }

        echo (new View())->render('Admin/category_view.php', [
            'title' => 'Kategória részletek - PetShop Admin',
            'category' => $this->entityManager->getRepository(Category::class)->find($categoryId),

        ]);
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

        echo (new View())->render('Admin/category_edit.php', [
            'title' => 'Kategória szerkesztése - PetShop Admin',
            'category' => $category,

        ]);
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

        echo (new View())->render('Admin/category_create.php', [
            'title' => 'Új kategória - PetShop Admin',

        ]);
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