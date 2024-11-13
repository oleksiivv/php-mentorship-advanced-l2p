<?php

namespace DesignPatterns\Decorator;

use DesignPatterns\AbstractFactory\PersonRepositoryInterface;
use Entities\Person;

class LowerCaseReadPersonDecorator implements PersonRepositoryInterface
{
    public function __construct(private PersonRepositoryInterface $personRepository)
    {
    }

    public function readPerson(string $name): Person|null
    {
        $person = $this->personRepository->readPerson($name);

        return $this->formatPerson($person);
    }

    public function readPeople(): array
    {
        $peoples = $this->personRepository->readPeople();

        return array_map(function (Person $person) {
            return $this->formatPerson($person);
        }, $peoples);
    }

    public function savePerson(Person $person): void
    {
        $this->personRepository->savePerson($person);
    }

    public function updatePersonIQ(Person $person, int $newIQ): void
    {
        $this->personRepository->updatePersonIQ($person, $newIQ);
    }

    private function formatPerson(Person|null $person): Person|null
    {
        if ($person !== null) {
            $person->setName(strtolower($person->getName()));
        }

        return $person;
    }
}
