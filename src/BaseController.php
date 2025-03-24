<?php

namespace Webshop;


class BaseController
{
    protected $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }
}
