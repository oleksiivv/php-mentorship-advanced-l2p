<?php

namespace DesignPatterns\AbstractFactory;

use Entities\Person;

interface PersonRepositoryInterface
{
    public const DB_STORAGE = 'DB';
    public const FS_STORAGE = 'FS';

    public function savePerson(Person $person): void;
    public function readPeople(): array;
    public function readPerson(string $name): Person|null;
    public function updatePersonIQ(Person $person, int $newIQ): void;
}