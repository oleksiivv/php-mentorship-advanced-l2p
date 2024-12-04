<?php

namespace Http;

use Core\Container;
use Http\Core\RequestInterface;
use Http\Core\Response;
use Http\Core\Session\SessionManager;
use Http\Middlewares\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;

class Router
{
    protected array $routes = [];
    protected Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function addRoute(string $method, string $path, string $controllerId, string $action, array $middlewares = []): void
    {
        $this->routes[$method][$path] = [$controllerId, $action, $middlewares];
    }

    public function matchRoute(): ResponseInterface
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = $_SERVER['REQUEST_URI'];

        $path = parse_url($url, PHP_URL_PATH);

        if (isset($this->routes[$method][$path])) {
            [$controllerId, $action, $middlewares] = $this->routes[$method][$path];

            try {
                $request = $this->container->get(RequestInterface::class);

                if ($request instanceof \Closure) {
                    $request = call_user_func($request, $this->container);
                }

                $request->setSessionManager($this->container->get(SessionManager::class));
                $request->addMiddlewares($middlewares);

                $response = array_reduce(array_reverse($request->getMiddlewares()), function ($next, $middleware) {
                    return function ($request) use ($middleware, $next) {
                        return $middleware->handle($this->container, $request, $next);
                    };
                }, function ($request) use ($controllerId, $action) {
                    $controller = $this->container->get($controllerId);
                    return call_user_func([$controller, $action], $request);
                })($request);

                if ($response instanceof ResponseInterface) {
                    $this->sendResponse($response);
                    return $response;
                }
            } catch (\Exception $e) {
                match ($e->getCode()) {
                    401 => $this->notAuthorizedResponse(),
                    404 => $this->notFoundResponse(),
                    default => $this->serverErrorResponse(),
                };
            }

            throw new \Exception('The controller action must return an instance of ResponseInterface.');
        }

        return $this->notFoundResponse();
    }

    protected function serverErrorResponse(): ResponseInterface
    {
        $response = new Response(['error' => 'Internal Server Error'], 500);
        $response = $response->withHeader('Content-Type', 'application/json');
        $this->sendResponse($response);

        return $response;
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

    protected function notAuthorizedResponse(): ResponseInterface
    {
        $response = new Response(['error' => 'Not Authorized'], 401);
        $response = $response->withHeader('Content-Type', 'application/json');
        $this->sendResponse($response);

        return $response;
    }
}
