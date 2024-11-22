<?php

namespace Tests\Unit\DesignPatterns\Facade;

use DesignPatterns\AbstractFactory\PersonRepositoryInterface;
use DesignPatterns\Facade\PersonIQService;
use Entities\Person;
use Exception;
use PHPUnit\Framework\TestCase;

class PersonIQServiceTest extends TestCase
{
    private PersonRepositoryInterface $personRepositoryMock;
    private PersonIQService $personIQService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->personRepositoryMock = $this->createMock(PersonRepositoryInterface::class);
        $this->personIQService = new PersonIQService($this->personRepositoryMock);
    }

    public function testWhoIsTheSmarter(): void
    {
        $person1 = new Person('Jane');
        $person2 = new Person('Joe');

        $person1->setIQ(50);
        $person2->setIQ(40);

        $this->personRepositoryMock->method('readPerson')
            ->willReturnMap([
                ['Jane', $person1],
                ['Joe', $person2]
            ]);

        $result = $this->personIQService->whoIsTheSmarter('Jane', 'Joe');

        $this->assertSame($person1, $result);
    }

    public function testTransferIq(): void
    {
        $person1 = new Person('Jane');
        $person2 = new Person('Joe');

        $person1->setIQ(50);
        $person2->setIQ(40);

        $this->personRepositoryMock->method('readPerson')
            ->willReturnMap([
                ['Jane', $person1],
                ['Joe', $person2]
            ]);

        $this->personRepositoryMock->expects($this->exactly(2))
            ->method('updatePersonIQ')
            ->withConsecutive(
                [$this->equalTo($person1), $this->equalTo(40)],
                [$this->equalTo($person2), $this->equalTo(50)]
            );

        $this->personIQService->transferIq('Jane', 'Joe', 10);
    }

    public function testChangeIqByDelta(): void
    {
        $person1 = new Person('Jane');
        $person1->setIQ(50);

        $this->personRepositoryMock->method('readPerson')
            ->willReturn($person1);

        $this->personRepositoryMock->expects($this->once())
            ->method('updatePersonIQ')
            ->with($this->equalTo($person1), $this->equalTo(60));

        $this->personIQService->changeIqByDelta('Alice', 10);
    }

    public function testTransferIqWithInsufficientIqPoints(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Not enough IQ points to transfer.');

        $person1 = new Person('Jane');
        $person2 = new Person('Joe');

        $person1->setIQ(5);
        $person2->setIQ(100);

        $this->personRepositoryMock->method('readPerson')
            ->willReturnMap([
                ['Jane', $person1],
                ['Joe', $person2]
            ]);

        $this->personIQService->transferIq('Jane', 'Joe', 10);
    }

    public function testTransferIqIfOnePersonNotFound(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Person with name Jane couldn\'t be found');

        $person = new Person('Joe');
        $person->setIQ(5);

        $this->personRepositoryMock->method('readPerson')
            ->willReturnMap([
                ['Jane', null],
                ['Joe', $person]
            ]);

        $this->personIQService->transferIq('Jane', 'Joe', 10);
    }
}
