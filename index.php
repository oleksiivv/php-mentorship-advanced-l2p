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

// Inventory routes
$router->addRoute('GET', '/inventory', \Http\Controllers\Order\InventoryController::class, 'index', [new AuthenticationMiddleware([UserRole::ROLE_USER])]);
$router->addRoute('POST', '/inventory', \Http\Controllers\Order\InventoryController::class, 'store', [new AuthenticationMiddleware([UserRole::ROLE_USER])]);
$router->addRoute('GET', '/inventory/show', \Http\Controllers\Order\InventoryController::class, 'show', [new AuthenticationMiddleware([UserRole::ROLE_USER])]);
$router->addRoute('PUT', '/inventory/update', \Http\Controllers\Order\InventoryController::class, 'update', [new AuthenticationMiddleware([UserRole::ROLE_USER])]);

// Cart routes
$router->addRoute('POST', '/cart', \Http\Controllers\Order\CartController::class, 'create', [new AuthenticationMiddleware([UserRole::ROLE_USER])]);
$router->addRoute('POST', '/cart/add', \Http\Controllers\Order\CartController::class, 'addToCart', [new AuthenticationMiddleware([UserRole::ROLE_USER])]);
$router->addRoute('GET', '/cart', \Http\Controllers\Order\CartController::class, 'checkout', [new AuthenticationMiddleware([UserRole::ROLE_USER])]);
$router->addRoute('DELETE', '/cart/remove', \Http\Controllers\Order\CartController::class, 'removeFromCart', [new AuthenticationMiddleware([UserRole::ROLE_USER])]);

$router->matchRoute();