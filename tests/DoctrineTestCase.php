<?php

namespace Tests;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;

trait DoctrineTestCase
{
    protected function getEntityManager(): EntityManagerInterface
    {
        $isDevMode = true;
        $proxyDir = null;
        $cache = null;
        $useSimpleAnnotationReader = false;

        $config = Setup::createAnnotationMetadataConfiguration(
            [__DIR__ . '/./../src/Entities'],
            $isDevMode,
            $proxyDir,
            $cache,
            $useSimpleAnnotationReader
        );

        $connection = [
            'driver' => 'pdo_sqlite',
            'path' => 'db-test.sqlite',
        ];

        $entityManager = EntityManager::create($connection, $config);

        $schema = new SchemaTool($entityManager);

        $schema->dropSchema($entityManager->getMetadataFactory()->getAllMetadata());
        $schema->createSchema($entityManager->getMetadataFactory()->getAllMetadata());

        return $entityManager;
    }
}
