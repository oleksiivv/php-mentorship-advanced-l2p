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

$connections = [
    'sqlite' => [
        'driver' => 'pdo_sqlite',
        'path' => 'db-test.sqlite',
    ],
    'mysql' => [
        'driver' => 'pdo_mysql',
        'host' => 'localhost',
        'port' => '3306',
        'user' => 'root',
        'password' => 'root',
        'dbname' => 'php_mentorship_l2p',
    ],
];

$entityManager = EntityManager::create($connections['mysql'], $config);

return $entityManager;
