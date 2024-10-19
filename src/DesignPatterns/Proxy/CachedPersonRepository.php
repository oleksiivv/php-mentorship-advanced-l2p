<?php

namespace DesignPatterns\Proxy;

use DesignPatterns\AbstractFactory\PersonRepositoryInterface;
use Entities\Person;

class CachedPersonRepository implements PersonRepositoryInterface
{
    private PersonRepositoryInterface $repository;
    private array $personCache = [];
    private ?array $peopleCache = null;

    public function __construct(PersonRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function savePerson(Person $person): void
    {
        $this->repository->savePerson($person);
        $this->invalidateCache();
    }

    public function readPeople(): array
    {
        if ($this->peopleCache === null) {
            $this->peopleCache = $this->repository->readPeople();
        }

        return $this->peopleCache;
    }

    public function readPerson(string $name): Person|null
    {
        if (!array_key_exists($name, $this->personCache)) {
            $this->personCache[$name] = $this->repository->readPerson($name);
        }

        return $this->personCache[$name];
    }

    public function updatePersonIQ(Person $person, int $newIQ): void
    {
        $this->repository->updatePersonIQ($person, $newIQ);
        $this->invalidateCache();
    }

    private function invalidateCache(): void
    {
        $this->personCache = [];
        $this->peopleCache = null;
    }
}