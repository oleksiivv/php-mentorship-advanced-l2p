<?php

namespace Tests\DesignPatterns\Decorator;

use DesignPatterns\AbstractFactory\DTO\PersonRepositoryData;
use PHPUnit\Framework\TestCase;
use DesignPatterns\AbstractFactory\PersonDBRepository;
use DesignPatterns\Decorator\LowerCaseReadPersonDecorator;
use DesignPatterns\Decorator\UpperCaseWritePersonDecorator;
use Tests\DoctrineTestCase;
use Entities\Person;

class PersonRepositoryDecoratorsTest extends TestCase
{
    use DoctrineTestCase;

    public function test_uppercase_saved_and_lowercase_returned(): void
    {
        $personRepository = new PersonDBRepository($this->getEntityManager());

        $personName = 'Tom';
        $person = new Person($personName);

        $lowerCaseDecorator = new LowerCaseReadPersonDecorator($personRepository);
        $upperCaseDecorator = new UpperCaseWritePersonDecorator($lowerCaseDecorator);

        $upperCaseDecorator->savePerson($person);

        $result = $upperCaseDecorator->readPerson('TOM');

        $this->assertEquals('tom', actual: $result->getName());
    }
}