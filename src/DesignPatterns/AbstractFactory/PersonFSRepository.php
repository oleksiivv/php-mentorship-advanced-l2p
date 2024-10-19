<?php

namespace DesignPatterns\AbstractFactory;

use Entities\Person;
use DesignPatterns\AbstractFactory\DTO\PersonRepositoryData;
use Exception;

class PersonFSRepository implements PersonRepositoryInterface
{
    private string $fileName;

    public function __construct(PersonRepositoryData $personRepositoryData)
    {
        if (!isset($personRepositoryData->filename)) {
            throw new Exception('Invalid configuration provided');
        }

        $this->fileName = $personRepositoryData->filename;
    }

    public function savePerson(Person $person): void
    {
        $people = $this->readPeople();
        
        $maxId = $this->getMaxId($people);
        $person->setId($maxId + 1);

        $people[] = $person;

        $this->writePeople($people);
    }

    public function readPeople(): array
    {
        if (! file_exists($this->fileName)) {
            return [];
        }

        $data = file_get_contents($this->fileName);
        $people = json_decode($data, true);

        return array_map(function ($personData) {
            return $this->deserializePerson($personData);
        }, $people ?? []);
    }

    public function readPerson(string $name): Person|null
    {
        $people = $this->readPeople();

        foreach ($people as $person) {
            if ($person->getName() === $name) {
                return $person;
            }
        }

        return null;
    }

    public function updatePersonIQ(Person $person, int $newIQ): void
    {
        $people = $this->readPeople();

        foreach ($people as &$storedPerson) {
            if ($storedPerson->getId() === $person->getId()) {
                $storedPerson->setIq($newIQ);
                break;
            }
        }

        $this->writePeople($people);
    }

    private function writePeople(array $people): void
    {
        $data = array_map(function (Person $person) {
            return $this->serializePerson($person);
        }, $people);

        file_put_contents($this->fileName, json_encode($data));
    }

    private function serializePerson(Person $person): array
    {
        return [
            'name' => $person->getName(),
            'id' => $person->getId(),
        ];
    }

    private function deserializePerson(array $data): Person
    {
        $person = new Person($data['name']);
        $person->setId($data['id']);

        return $person;
    }

    private function getMaxId(array $people): int
    {
        $maxId = 0;

        foreach ($people as $person) {
            if ($person->getId() > $maxId) {
                $maxId = $person->getId();
            }
        }

        return $maxId;
    }
}
