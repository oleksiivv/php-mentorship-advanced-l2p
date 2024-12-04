<?php

namespace Http\Middlewares;

use Core\Container;
use Http\Core\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface MiddlewareInterface
{
    public function handle(Container $container, RequestInterface $request, callable $next): ResponseInterface;
}