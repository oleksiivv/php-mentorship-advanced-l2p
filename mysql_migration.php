<?php

require_once "vendor/autoload.php";

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

$container = require __DIR__ . '/src/Core/services.php';

$entityManager = $container->get(EntityManagerInterface::class);

$metaData = $entityManager->getMetadataFactory()->getAllMetadata();

$schemaTool = new SchemaTool($entityManager);

try {
    echo "Dropping existing schema...\n";
    $schemaTool->dropSchema($metaData);
    echo "Existing schema dropped successfully.\n";

    echo "Creating database schema...\n";
    $schemaTool->createSchema($metaData);
    echo "Database schema created successfully.\n";
} catch (Exception $e) {
    echo "An error occurred while creating the schema: " . $e->getMessage() . "\n";
}