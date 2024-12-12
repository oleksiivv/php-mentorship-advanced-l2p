<?php

namespace Http\Core;

use Entities\User;
use Enums\UserRole;
use Http\Core\Session\SessionManager;
use Http\Middlewares\MiddlewareInterface;
use Psr\Http\Message\UriInterface;

class Request extends HttpMessage implements RequestInterface
{
    protected array $query;
    protected array|null $request;
    protected array $server;
    protected string $requestTarget;
    protected UriInterface|null $uri;
    protected array $middlewares = [];
    private SessionManager $sessionManager;

    private User|null $user;

    public function __construct(UriInterface $uri = null)
    {
        $this->query = $_GET;
        $this->server = $_SERVER;
        $this->uri = $uri;
        $this->headers = getallheaders();

        $this->handleRequestData();
        $this->requestTarget = $this->server['REQUEST_URI'] ?? '/';
    }

    public function setSessionManager(SessionManager $sessionManager)
    {
        $this->sessionManager = $sessionManager;
    }

    public function getSessionData($key): mixed
    {
        return $this->sessionManager->get($key);
    }

    public function setSessionData($key, $value): void
    {
        $this->sessionManager->set($key, $value);
    }

    public function getCsrfToken(): string|null
    {
        return $this->getSessionData('csrf_token');
    }

    public function setCsrfToken($token): void
    {
        $this->setSessionData('csrf_token', $token);
    }

    public function addMiddlewares(array $middlewares): void
    {
        $this->middlewares = array_merge($this->middlewares, $middlewares);
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    private function handleRequestData(): void
    {
        if ($this->getContentType() === 'application/json') {
            $jsonContent = strip_tags(file_get_contents('php://input'));
            $decodedJson = json_decode($jsonContent, true);

            $this->request = is_array($decodedJson) ? $decodedJson : [];
        } else {
            $this->request = $_POST;
        }
    }

    private function getContentType(): ?string
    {
        return $this->server['CONTENT_TYPE'] ?? $this->server['HTTP_CONTENT_TYPE'] ?? null;
    }

    public function getQuery($key, $default = null): ?string
    {
        return $this->query[$key] ?? $default;
    }

    public function getRequest($key, $default = null): ?string
    {
        return $this->request[$key] ?? $default;
    }

    public function getMethod(): string
    {
        return $this->server['REQUEST_METHOD'];
    }

    public function getRequestTarget(): string
    {
        return $this->requestTarget;
    }

    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        $new = clone $this;
        $new->requestTarget = $requestTarget;
        return $new;
    }

    public function withMethod(string $method): RequestInterface
    {
        $new = clone $this;
        $new->server['REQUEST_METHOD'] = $method;
        return $new;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        $new = clone $this;
        $new->uri = $uri;

        if ($preserveHost && $uri->getHost() !== '') {
            $new->server['HTTP_HOST'] = $uri->getHost();
        }

        return $new;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getUser(): User|null
    {
        return $this->user;
    }
}
