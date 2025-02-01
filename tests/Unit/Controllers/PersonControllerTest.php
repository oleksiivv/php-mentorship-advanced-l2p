<?php

namespace Tests\Unit\Controllers;

use Http\Controllers\PersonController;
use DesignPatterns\AbstractFactory\PersonRepositoryInterface;
use Http\Core\Request;
use Entities\Person;
use PHPUnit\Framework\TestCase;

class PersonControllerTest extends TestCase
{
    private PersonRepositoryInterface $personRepository;
    private PersonController $controller;
    private Request $request;

    protected function setUp(): void
    {
        $this->personRepository = $this->createMock(PersonRepositoryInterface::class);
        $this->controller = new PersonController($this->personRepository);
        $this->request = $this->createMock(Request::class);
    }

    public function testIndex()
    {
        $people = [new Person('John'), new Person('Jane')];
        $this->personRepository->method('readPeople')->willReturn($people);

        $response = $this->controller->index($this->request);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testStore()
    {
        $this->request->method('getRequest')->with('name')->willReturn('Alice');
        $this->personRepository->expects($this->once())->method('savePerson')->with($this->callback(function ($person) {
            return $person instanceof Person && $person->getName() === 'Alice';
        }));

        $response = $this->controller->store($this->request);

        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testShowPersonExists()
    {
        $this->request->method('getQuery')->with('name')->willReturn('Bob');
        $this->personRepository->method('readPerson')->with('Bob')->willReturn(new Person('Bob'));

        $response = $this->controller->show($this->request);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testShowPersonDoesNotExist()
    {
        $this->request->method('getQuery')->with('name')->willReturn('Charlie');
        $this->personRepository->method('readPerson')->with('Charlie')->willReturn(null);

        $response = $this->controller->show($this->request);

        $this->assertEquals(404, $response->getStatusCode());
    }
}