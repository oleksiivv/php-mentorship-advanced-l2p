<?php

require_once __DIR__ . '/src/Http/Router.php';

require 'vendor/autoload.php';

use Http\Router;

$container = require __DIR__ . '/src/Core/services.php';

$router = new Router($container);

$router->addRoute('GET', '/', \Http\Controllers\TestController::class, 'index');
$router->addRoute('POST', '/', \Http\Controllers\TestController::class, 'store');
$router->addRoute('GET', '/find', \Http\Controllers\TestController::class, 'show');

$router->matchRoute();