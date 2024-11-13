<?php

namespace Http\Controllers;

use DesignPatterns\AbstractFactory\PersonRepositoryInterface;
use Entities\Person;
use Http\Requests\Request;
use Http\Responses\JsonResponse;

class TestController
{
    private PersonRepositoryInterface $personRepository;

    public function __construct(PersonRepositoryInterface $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    public function index(Request $request): JsonResponse
    {
        return new JsonResponse([$this->preparePersonData($this->personRepository->readPeople())]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->personRepository->savePerson(new Person($request->getRequest('name')));

        return new JsonResponse(['message' => 'Person saved'], 201);
    }

    public function show(Request $request): JsonResponse
    {
        $person = $this->personRepository->readPerson($request->getQuery('name'));
        $status = $person ? 200 : 404;

        return new JsonResponse($this->preparePersonData([$person]), $status);
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
