<?php

namespace Tests\Feature;

use Entities\Person;
use Tests\FeatureTestCase;

class PersonControllerTest extends FeatureTestCase
{
    public function testPostPersonAddsNewPerson()
    {
        $data = ['json' => ['name' => 'John']];

        $response = $this->sendRequest('POST', '/', $data);

        $this->assertEquals(201, $response->getStatusCode());

        $this->assertDatabaseHas(Person::class, $data['json']);
    }

    public function testFindPersonReturnsPerson()
    {
        $personName = 'John';

        $this->entityManager->persist(new Person($personName));
        $this->entityManager->flush();

        $response = $this->sendRequest('GET', '/find?name=' . $personName);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([['name' => $personName]], json_decode($response->getBody()->getContents(), true));
    }

    public function testFindPersonReturns404IfPersonNotFound()
    {
        $response = $this->sendRequest('GET', '/find?name=John3ewe');

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testGetPeopleReturnsPeople()
    {
        $firstPersonName = 'John';
        $secondPersonName = 'Jane';

        $this->entityManager->persist(new Person($firstPersonName));
        $this->entityManager->persist(new Person($secondPersonName));

        $this->entityManager->flush();

        $response = $this->sendRequest('GET', '/');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([
            ['name' => $firstPersonName],
            ['name' => $secondPersonName],
        ], json_decode($response->getBody()->getContents(), true));
    }
}
