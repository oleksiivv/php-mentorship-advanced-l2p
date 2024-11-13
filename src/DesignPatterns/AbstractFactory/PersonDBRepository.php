<?php

namespace DesignPatterns\AbstractFactory;

use Entities\Person;
use Doctrine\ORM\EntityManagerInterface;

class PersonDBRepository implements PersonRepositoryInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
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
