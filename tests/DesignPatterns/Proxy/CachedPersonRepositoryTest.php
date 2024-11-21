<?php

namespace Tests\DesignPatterns\Proxy;

use PHPUnit\Framework\TestCase;
use DesignPatterns\Proxy\CachedPersonRepository;
use DesignPatterns\AbstractFactory\PersonRepositoryInterface;
use Entities\Person;
use PHPUnit\Framework\MockObject\MockObject;

class CachedPersonRepositoryTest extends TestCase
{
    private PersonRepositoryInterface|MockObject $personRepositoryMock;
    private CachedPersonRepository $cachedRepository;
    private Person $person;

    protected function setUp(): void
    {
        $this->personRepositoryMock = $this->createMock(PersonRepositoryInterface::class);
        $this->cachedRepository = new CachedPersonRepository($this->personRepositoryMock);

        $this->person = new Person('John Doe');
        $this->person->setId(100);
    }

    public function testReadPeopleUsesCache(): void
    {
        $people = [$this->person];

        $this->personRepositoryMock
            ->expects($this->once())
            ->method('readPeople')
            ->willReturn($people);

        $result1 = $this->cachedRepository->readPeople();
        $this->assertSame($people, $result1);

        $result2 = $this->cachedRepository->readPeople();
        $this->assertSame($people, $result2);
    }

    public function testReadPersonUsesCache(): void
    {
        $this->personRepositoryMock
            ->expects($this->once())
            ->method('readPerson')
            ->with('John Doe')
            ->willReturn($this->person);

        $result1 = $this->cachedRepository->readPerson('John Doe');
        $this->assertSame($this->person, $result1);

        $result2 = $this->cachedRepository->readPerson('John Doe');
        $this->assertSame($this->person, $result2);
    }

    public function testCacheInvalidatesOnSavePerson(): void
    {
        $people = [$this->person];

        $this->personRepositoryMock
            ->expects($this->exactly(2))
            ->method('readPeople')
            ->willReturn($people);

        $this->assertSame($people, $this->cachedRepository->readPeople());

        $this->personRepositoryMock
            ->expects($this->once())
            ->method('savePerson')
            ->with($this->person);

        $this->cachedRepository->savePerson($this->person);

        $this->assertSame($people, $this->cachedRepository->readPeople());
    }

    public function testCacheInvalidatesOnUpdatePersonIQ(): void
    {
        $people = [$this->person];

        $this->personRepositoryMock
            ->expects($this->exactly(2))
            ->method('readPeople')
            ->willReturn($people);

        $this->cachedRepository->readPeople();

        $this->personRepositoryMock
            ->expects($this->once())
            ->method('updatePersonIQ')
            ->with($this->person, 130);

        $this->cachedRepository->updatePersonIQ($this->person, 130);

        $this->assertSame($people, $this->cachedRepository->readPeople());
    }
}
