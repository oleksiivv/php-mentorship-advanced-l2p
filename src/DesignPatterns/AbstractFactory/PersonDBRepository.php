<?php

namespace DesignPatterns\AbstractFactory;

use Entities\Person;
use Doctrine\ORM\EntityManagerInterface;
use DesignPatterns\AbstractFactory\DTO\PersonRepositoryData;

class PersonDBRepository implements PersonRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(PersonRepositoryData $personRepositoryData)
    {
        if (!isset($personRepositoryData->entityManager)) {
            throw new Exception('Invalid configuration provided');
        }

        $this->entityManager = $personRepositoryData->entityManager;
    }

    public function savePerson(Person $person): void
    {
        $this->entityManager->persist($person);
        $this->entityManager->flush();
    }

    public function readPeople(): array
    {
        $repository = $this->entityManager->getRepository(Person::class);
        
        return $repository->findAll();
    }

    public function readPerson(string $name): Person|null
    {
        $repository = $this->entityManager->getRepository(Person::class);
        return $repository->findOneBy(['name' => $name]);
    }

    public function updatePersonIQ(Person $person, int $newIQ): void
    {
        $person->setIq($newIQ);

        $this->entityManager->flush();
    }
}