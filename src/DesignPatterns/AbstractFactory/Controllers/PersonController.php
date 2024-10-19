<?php

namespace DesignPatterns\AbstractFactory\Controllers;

use DesignPatterns\AbstractFactory\PersonRepositoryInterface;
use DesignPatterns\AbstractFactory\PersonDBRepository;
use DesignPatterns\AbstractFactory\PersonFSRepository;
use Entities\Person;
use DesignPatterns\AbstractFactory\DTO\PersonRepositoryData;

class PersonController
{
    private PersonRepositoryInterface $personRepository;

    public function __construct(string $storageType, PersonRepositoryData $personRepositoryData)
    {
        $this->personRepository = match ($storageType) {
            PersonRepositoryInterface::DB_STORAGE => new PersonDBRepository($personRepositoryData),
            PersonRepositoryInterface::FS_STORAGE => new PersonFSRepository($personRepositoryData),
            default => throw new \Exception('Invalid storage type provided'),
        };
    }

    public function savePerson(string $name): void
    {
        $this->personRepository->savePerson(new Person($name));
    }

    public function readPeople(): array
    {
        return $this->personRepository->readPeople();
    }

    public function readPerson(string $name): Person|null
    {
        return $this->personRepository->readPerson($name);
    }
}