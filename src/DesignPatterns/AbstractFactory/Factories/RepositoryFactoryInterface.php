<?php

namespace DesignPatterns\AbstractFactory\Factories;

use DesignPatterns\AbstractFactory\DTO\PersonRepositoryData;
use DesignPatterns\AbstractFactory\PersonRepositoryInterface;

interface RepositoryFactoryInterface
{
    public function createPersonRepository(PersonRepositoryData $data): PersonRepositoryInterface;
}