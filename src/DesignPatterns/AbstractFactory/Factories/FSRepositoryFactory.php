<?php

namespace DesignPatterns\AbstractFactory\Factories;

use DesignPatterns\AbstractFactory\DTO\PersonRepositoryData;
use DesignPatterns\AbstractFactory\PersonFSRepository;
use DesignPatterns\AbstractFactory\PersonRepositoryInterface;
use Exception;

class FSRepositoryFactory implements RepositoryFactoryInterface
{
    public function createPersonRepository(PersonRepositoryData $data): PersonRepositoryInterface
    {
        if (! isset($data->filename)) {
            throw new Exception('Invalid data provided');
        }

        return new PersonFSRepository($data->filename);
    }
}