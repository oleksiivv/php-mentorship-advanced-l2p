<?php

namespace DesignPatterns\Decorator;

use DesignPatterns\AbstractFactory\PersonRepositoryInterface;
use Entities\Person;

class UppercaseWritePersonDecorator implements PersonRepositoryInterface
{
    public function __construct(private PersonRepositoryInterface $personRepository)
    {
    }

    public function readPerson(string $name): Person|null
    {
        return $this->personRepository->readPerson($name);
    }

    public function readPeople(): array
    {
        return $this->readPeople();
    }

    public function savePerson(Person $person): void
    {
        $person->setName(strtoupper($person->getName()));
        $this->personRepository->savePerson($person);
    }

    public function updatePersonIQ(Person $person, int $newIQ): void
    {
        $this->personRepository->updatePersonIQ($person, $newIQ);
    }
}