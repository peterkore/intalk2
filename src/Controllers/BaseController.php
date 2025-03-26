<?php

namespace Webshop\Controllers;

use Doctrine\ORM\EntityManager;

abstract class BaseController
{
    protected EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
} 