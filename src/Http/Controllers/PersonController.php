<?php

namespace Http\Controllers;

use DesignPatterns\AbstractFactory\PersonRepositoryInterface;
use Entities\Person;
use Http\Core\Request;
use Http\Core\Requests\IndexPersonRequest;
use Http\Core\Requests\ShowPersonRequest;
use Http\Core\Requests\StorePersonRequest;
use Http\Core\Response;

class PersonController
{
    private PersonRepositoryInterface $personRepository;

    public function __construct(PersonRepositoryInterface $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    public function index(IndexPersonRequest $request): Response
    {
        return new Response($this->preparePersonData($this->personRepository->readPeople()), 200);
    }

    public function store(StorePersonRequest $request): Response
    {
        $this->personRepository->savePerson(new Person($request->getRequest('name')));

        return new Response(['message' => 'Person saved'], 201);
    }

    public function show(ShowPersonRequest $request): Response
    {
        $person = $this->personRepository->readPerson($request->getQuery('name'));
        $status = $person ? 200 : 404;

        return new Response($this->preparePersonData([$person]), $status);
    }

    private function preparePersonData(array $data): array
    {
        return array_filter(
            array_map(function (Person|null $person) {
                if ($person === null) {
                    return null;
                }

                return [
                    'name' => $person->getName(),
                ];
            }, $data)
        );
    }
}
