<?php

namespace DesignPatterns\Facade;

use Entities\Person;
use Designpatterns\AbstractFactory\PersonRepositoryInterface;
use Exception;

class PersonIQService
{
    public function __construct(private PersonRepositoryInterface $personRepository)
    {
    }

    public function whoIsTheSmarter(string $person1Name, string $person2Name): Person
    {
        $person1 = $this->finPersonByName($person1Name);
        $person2 = $this->finPersonByName($person2Name);

        if ($person1->getIq() > $person2->getIq()) {
            return $person1;
        }

        return $person2;
    }

    public function transferIq(string $fromName, string $toName, int $amountToTransfer): void {
        $fromPerson = $this->finPersonByName($fromName);
        $toPerson = $this->finPersonByName($toName);

        if ($fromPerson->getIq() - $amountToTransfer < 0) {
            throw new Exception('Not enough IQ points to transfer.');
        }

        $this->personRepository->updatePersonIQ($fromPerson, $fromPerson->getIq() - $amountToTransfer);
        $this->personRepository->updatePersonIQ($toPerson, $toPerson->getIq() + $amountToTransfer);
    }

    public function changeIqByDelta(string $personName, int $delta): void
    {
        $person = $this->finPersonByName($personName);

        $this->personRepository->updatePersonIQ($person, $person->getIq() + $delta);
    }

    private function finPersonByName(string $name): Person
    {
        $person = $this->personRepository->readPerson($name);

        if ($person === null) {
            throw new Exception(sprintf('Person with name %s couldn\'t be found', $name));
        }

        return $person;
    }
}
