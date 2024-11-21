<?php

namespace Http;

use Core\Container;
use Http\Core\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Router
{
    protected array $routes = [];
    protected Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function addRoute(string $method, string $path, $controllerId, $action): void
    {
        $this->routes[$method][$path] = [$controllerId, $action];
    }

    public function matchRoute(): ResponseInterface
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = $_SERVER['REQUEST_URI'];

        $path = parse_url($url, PHP_URL_PATH);

        if (isset($this->routes[$method][$path])) {
            [$controllerId, $action] = $this->routes[$method][$path];

            $controller = $this->container->get($controllerId);
            $request = $this->container->get(RequestInterface::class);

            if ($request instanceof \Closure) {
                $request = call_user_func($request, $this->container);
            }

            $response = call_user_func([$controller, $action], $request);

            if ($response instanceof ResponseInterface) {
                $this->sendResponse($response);
                return $response;
            }

            throw new \Exception('The controller action must return an instance of ResponseInterface.');
        }

        return $this->notFoundResponse();
    }

    protected function sendResponse(ResponseInterface $response): void
    {
        http_response_code($response->getStatusCode());

        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header("$name: $value", false);
            }
        }

        echo $response->getBody()->getContents();
    }

    protected function notFoundResponse(): ResponseInterface
    {
        $response = new Response(['error' => 'Page Not Found'], 404);
        $response = $response->withHeader('Content-Type', 'application/json');
        $this->sendResponse($response);

        return $response;
    }
}
