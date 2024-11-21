<?php

namespace DesignPatterns\AbstractFactory\Factories;

use DesignPatterns\AbstractFactory\DTO\PersonRepositoryData;
use DesignPatterns\AbstractFactory\PersonDBRepository;
use DesignPatterns\AbstractFactory\PersonRepositoryInterface;
use Exception;

class DBRepositoryFactory implements RepositoryFactoryInterface
{
    public function createPersonRepository(PersonRepositoryData $data): PersonRepositoryInterface
    {
        if (! isset($data->entityManager)) {
            throw new Exception('Invalid data provided');
        }

        return new PersonDBRepository($data->entityManager);
    }
}
