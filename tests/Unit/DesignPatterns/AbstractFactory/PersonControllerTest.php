<?php

namespace Tests\Unit\DesignPatterns\AbstractFactory;

use DesignPatterns\AbstractFactory\Controllers\PersonController;
use DesignPatterns\AbstractFactory\DTO\PersonRepositoryData;
use DesignPatterns\AbstractFactory\Factories\DBRepositoryFactory;
use DesignPatterns\AbstractFactory\Factories\FSRepositoryFactory;
use DesignPatterns\AbstractFactory\Factories\RepositoryFactoryInterface;
use PHPUnit\Framework\TestCase;
use Tests\DoctrineTestCase;
use Tests\FileTestCase;

class PersonControllerTest extends TestCase
{
    use DoctrineTestCase;
    use FileTestCase;

    private PersonRepositoryData $personRepositoryData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->personRepositoryData = new PersonRepositoryData(
            $this->refreshFile('test.txt'),
            $this->getEntityManager(),
        );
    }

    /**
     * @dataProvider repositoryFactoryProvider
     */
    public function testSaveAndReadPeople(RepositoryFactoryInterface $repositoryFactory): void
    {
        $controller = new PersonController($repositoryFactory, $this->personRepositoryData);

        $personName = 'John Doe';

        $controller->savePerson($personName);

        $result = $controller->readPeople();

        $this->assertCount(1, $result);
        $this->assertEquals($personName, $result[0]->getName());
    }

    /**
     * @dataProvider repositoryFactoryProvider
     */
    public function testSaveAndReadPerson(RepositoryFactoryInterface $repositoryFactory): void
    {
        $controller = new PersonController($repositoryFactory, $this->personRepositoryData);

        $personName = 'John Doe';

        $controller->savePerson($personName);

        $result = $controller->readPerson($personName);

        $this->assertSame($personName, $result->getName());
    }

    public function repositoryFactoryProvider(): array
    {
        return [
            'DB Storage' => [new DBRepositoryFactory()],
            'FS Storage' => [new FSRepositoryFactory()],
        ];
    }
}
