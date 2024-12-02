<?php

namespace Http\Core;

use Entities\UserRole;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class Request extends HttpMessage implements RequestInterface
{
    protected array $query;
    protected array|null $request;
    protected array $server;
    protected string $requestTarget;
    protected UriInterface|null $uri;

    protected string|null $requiredRole = UserRole::ROLE_USER;

    public function __construct(UriInterface $uri = null)
    {
        $this->query = $_GET;
        $this->server = $_SERVER;
        $this->uri = $uri;

        $this->validateAccess();

        $this->handleRequestData();
        $this->requestTarget = $this->server['REQUEST_URI'] ?? '/';
    }

    private function handleRequestData(): void
    {
        if ($this->getContentType() === 'application/json') {
            $jsonContent = htmlspecialchars(file_get_contents('php://input'), ENT_QUOTES, 'UTF-8');
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

    private function validateAccess(): void
    {
        $accessToken = $this->request['accessToken'] ?? null;

        if ($accessToken === null || hex2bin($accessToken) === false) {
            throw new \Exception('Access denied', 401);
        }

        $roles = json_decode(hex2bin($accessToken), true)['roles'] ?? [];

        if (!in_array($this->requiredRole, $roles)) {
            throw new \Exception('Access denied', 401);
        }
    }
}
