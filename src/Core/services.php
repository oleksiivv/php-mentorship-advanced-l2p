<?php

use Core\Container;
use DesignPatterns\AbstractFactory\PersonDBRepository;
use DesignPatterns\AbstractFactory\PersonRepositoryInterface;
use Http\Core\Request;
use Http\Core\Uri;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

require 'vendor/autoload.php';

$entityManager = require_once __DIR__ . '/../config/bootstrap.php';

$container = new Container();
const PROTOCOL_VERSION = '1.1';

$container->bind(UriInterface::class, new Uri());

$container->bind(RequestInterface::class, function (Container $container) {
    $uri = $container->get(UriInterface::class);
    $request = new Request($uri);
    return $request->withProtocolVersion(PROTOCOL_VERSION);
});

$container->bind(PersonRepositoryInterface::class, new PersonDBRepository($entityManager));

return $container;
