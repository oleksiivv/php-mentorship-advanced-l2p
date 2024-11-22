<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once __DIR__ . '/../../vendor/autoload.php';

$isDevMode = true;
$proxyDir = null;
$cache = null;
$useSimpleAnnotationReader = false;

$config = Setup::createAnnotationMetadataConfiguration(
    [__DIR__ . './../src/Entities'],
    $isDevMode,
    $proxyDir,
    $cache,
    $useSimpleAnnotationReader
);

$connection = [
    'driver' => 'pdo_sqlite',
    'path' => 'db-test.sqlite', //Change to real db instead of test one
];

$entityManager = EntityManager::create($connection, $config);

return $entityManager;
