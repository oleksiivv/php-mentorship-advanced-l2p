<?php

namespace DesignPatterns\AbstractFactory\Controllers;

use DesignPatterns\AbstractFactory\Factories\RepositoryFactoryInterface;
use DesignPatterns\AbstractFactory\PersonRepositoryInterface;
use Entities\Person;
use DesignPatterns\AbstractFactory\DTO\PersonRepositoryData;

class PersonController
{
    private PersonRepositoryInterface $personRepository;

    public function __construct(
        RepositoryFactoryInterface $repositoryFactory,
        PersonRepositoryData $personRepositoryData
    ) {
        $this->personRepository = $repositoryFactory->createPersonRepository($personRepositoryData);
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