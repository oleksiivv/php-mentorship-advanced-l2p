<?php

require_once __DIR__ . '/src/Http/Router.php';

require 'vendor/autoload.php';

use Http\Router;

$container = require __DIR__ . '/src/Core/services.php';

$router = new Router($container);

$router->addRoute('GET', '/', \Http\Controllers\PersonController::class, 'index', [\Entities\UserRole::ROLE_GUEST]);
$router->addRoute('POST', '/', \Http\Controllers\PersonController::class, 'store', [\Entities\UserRole::ROLE_ADMIN]);
$router->addRoute('GET', '/find', \Http\Controllers\PersonController::class, 'show', [\Entities\UserRole::ROLE_USER]);

$router->addRoute('POST', '/auth/login', \Http\Controllers\AuthController::class, 'login', [\Entities\UserRole::ROLE_GUEST]);
$router->addRoute('POST', '/auth/register', \Http\Controllers\AuthController::class, 'register', [\Entities\UserRole::ROLE_GUEST]);

$router->matchRoute();