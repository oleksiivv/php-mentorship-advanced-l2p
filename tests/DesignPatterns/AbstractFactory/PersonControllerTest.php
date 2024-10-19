<?php

namespace Tests\DesignPatterns\AbstractFactory;

use DesignPatterns\AbstractFactory\Controllers\PersonController;
use DesignPatterns\AbstractFactory\PersonRepositoryInterface;
use Entities\Person;
use PHPUnit\Framework\TestCase;
use DesignPatterns\AbstractFactory\DTO\PersonRepositoryData;
use Doctrine\ORM\EntityManagerInterface;
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
     * @dataProvider storageTypeProvider
     */
    public function testSaveAndReadPeople(string $storageType): void
    {
        $controller = new PersonController($storageType, $this->personRepositoryData);

        $personName = 'John Doe';

        $controller->savePerson($personName);

        $result = $controller->readPeople();

        $this->assertCount(1, $result);
        $this->assertEquals($personName, $result[0]->getName());
    }

    /**
     * @dataProvider storageTypeProvider
     */
    public function testSaveAndReadPerson(string $storageType): void
    {
        $controller = new PersonController($storageType, $this->personRepositoryData);

        $personName = 'John Doe';

        $controller->savePerson($personName);

        $result = $controller->readPerson($personName);

        $this->assertSame($personName, $result->getName());
    }

    public function storageTypeProvider(): array
    {
        return [
            'DB Storage' => [PersonRepositoryInterface::DB_STORAGE],
            'FS Storage' => [PersonRepositoryInterface::FS_STORAGE],
        ];
    }
}
