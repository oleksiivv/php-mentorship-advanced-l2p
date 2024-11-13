<?php

use Core\Container;
use Http\Requests\Request;

require 'vendor/autoload.php';

$entityManager = require_once __DIR__ . '/../config/bootstrap.php';

$container = new Container();

$container->bind('request', new Request());
$container->bind('personRepository', new \DesignPatterns\AbstractFactory\PersonDBRepository($entityManager));

return $container;
