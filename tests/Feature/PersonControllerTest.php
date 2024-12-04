<?php

namespace Tests\Feature;

use Core\Container;
use Entities\Person;
use Entities\User;
use Enums\UserRole;
use Http\Core\Session\SessionManager;
use Tests\FeatureTestCase;

class PersonControllerTest extends FeatureTestCase
{
    protected User $user;

    protected User $userAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = new User();
        $this->user->setName('John Doe');
        $this->user->setEmail('doe@gmail.com');
        $this->user->setPasswordSha(sha1('password'));
        $this->user->setRoles([UserRole::ROLE_USER]);
        $this->user->setAccessToken(bin2hex(json_encode([
            'token' => sha1($this->user->getEmail() . $this->user->getPasswordSha()),
            'roles' => $this->user->getRoles(),
        ])));

        $this->userAdmin = new User();
        $this->userAdmin->setName('Jack Doe');
        $this->userAdmin->setEmail('doe2@gmail.com');
        $this->userAdmin->setPasswordSha(sha1('password'));
        $this->userAdmin->setRoles([UserRole::ROLE_USER, UserRole::ROLE_ADMIN]);
        $this->userAdmin->setAccessToken(bin2hex(json_encode([
            'token' => sha1($this->userAdmin->getEmail() . $this->userAdmin->getPasswordSha()),
            'roles' => $this->userAdmin->getRoles(),
        ])));

        $this->entityManager->persist($this->user);
        $this->entityManager->persist($this->userAdmin);

        $this->entityManager->flush();
    }

    public function testPostPersonAddsNewPerson()
    {
        $data = [
            'json' => ['name' => 'John'],
            'headers' => ['Authorization' => 'Bearer ' . $this->userAdmin->getAccessToken()],
        ];

        $response = $this->sendRequest('POST', '/', $data);

        $this->assertEquals(201, $response->getStatusCode());

        $this->assertDatabaseHas(Person::class, $data['json']);
    }

    public function testFindPersonReturnsPerson()
    {
        $personName = 'John';

        $this->entityManager->persist(new Person($personName));
        $this->entityManager->flush();

        $response = $this->sendRequest(
            'GET', '/find?name=' . $personName,
            ['headers' => ['Authorization' => 'Bearer ' . $this->user->getAccessToken()]],
        );

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([['name' => $personName]], json_decode($response->getBody()->getContents(), true));
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
