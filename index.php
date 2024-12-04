<?php

require_once __DIR__ . '/src/Http/Router.php';

require 'vendor/autoload.php';

use Enums\UserRole;
use Http\Core\Session\SessionManager;
use Http\Middlewares\AuthenticationMiddleware;
use Http\Middlewares\CSRFMiddleware;
use Http\Router;

$container = require __DIR__ . '/src/Core/services.php';

$router = new Router($container);

$router->addRoute('GET', '/', \Http\Controllers\PersonController::class, 'index');
$router->addRoute('POST', '/', \Http\Controllers\PersonController::class, 'store', [
    new AuthenticationMiddleware([UserRole::ROLE_ADMIN]),
    new CSRFMiddleware($container->get(SessionManager::class))
]);


$router->addRoute('GET', '/find', \Http\Controllers\PersonController::class, 'show', [new AuthenticationMiddleware([UserRole::ROLE_USER])]);

$router->addRoute('POST', '/auth/login', \Http\Controllers\AuthController::class, 'login', [new AuthenticationMiddleware([UserRole::ROLE_GUEST])]);
$router->addRoute('POST', '/auth/register', \Http\Controllers\AuthController::class, 'register', [new AuthenticationMiddleware([UserRole::ROLE_GUEST])]);

$router->matchRoute();