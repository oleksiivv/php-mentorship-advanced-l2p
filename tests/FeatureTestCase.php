<?php

namespace Tests;

use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class FeatureTestCase extends TestCase
{
    use DoctrineTestCase;

    private Client $client;

    protected EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client(['base_uri' => 'http://127.0.0.1:8080']);
        $this->entityManager = $this->getEntityManager();
    }

    protected function sendRequest(string $method, string $uri, array $data = []): ResponseInterface
    {
        try {
            return $this->client->request($method, $uri, $data);
        } catch (Throwable $e) {
            return $e->getResponse();
        }
    }

    protected function assertDatabaseHas(string $entity, array $data): void
    {
        $entity = $this->entityManager->getRepository($entity)->findOneBy($data);

        $this->assertNotNull($entity);
    }
}
