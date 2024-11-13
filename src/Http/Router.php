<?php

namespace Http;

use Core\Container;
use Http\Responses\JsonResponse;

class Router
{
    protected array $routes = [];
    protected Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function addRoute(string $method, string $url, $controllerId, $action): void
    {
        $this->routes[$method][$url] = [$controllerId, $action];
    }

    public function matchRoute(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = $_SERVER['REQUEST_URI'];

        $path = parse_url($url, PHP_URL_PATH);

        if (isset($this->routes[$method][$path])) {
            [$controllerId, $action] = $this->routes[$method][$path];

            $controller = $this->container->get($controllerId);
            $request = $this->container->get('request');

            $response = call_user_func([$controller, $action], $request);

            if ($response instanceof JsonResponse) {
                http_response_code($response->getStatusCode());
                echo $response->getContent();
            }
        } else {
            http_response_code(404);
            echo "Page Not Found";
        }
    }
}
