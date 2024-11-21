<?php

namespace Http\Core;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

class ServerRequest extends Request implements ServerRequestInterface
{
    protected array $attributes = [];
    protected array $cookieParams = [];
    protected array $queryParams = [];
    protected array $uploadedFiles = [];
    protected array|null|object $parsedBody;
    protected array $serverParams;

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getHeader($name): array
    {
        return $this->headers[$name] ?? [];
    }

    public function withHeader($name, $value): self
    {
        $new = clone $this;
        $new->headers[$name] = [$value];
        return $new;
    }

    public function getServerParams(): array
    {
        return $this->serverParams;
    }

    public function getCookieParams(): array
    {
        return $this->cookieParams;
    }

    public function withCookieParams(array $cookies): ServerRequestInterface
    {
        $new = clone $this;
        $new->cookieParams = $cookies;
        return $new;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function withQueryParams(array $query): ServerRequestInterface
    {
        $new = clone $this;
        $new->queryParams = $query;
        return $new;
    }

    public function getUploadedFiles(): array
    {
        return $this->uploadedFiles;
    }

    public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface
    {
        $new = clone $this;
        $new->uploadedFiles = $uploadedFiles;
        return $new;
    }

    public function getParsedBody()
    {
        return $this->parsedBody;
    }

    public function withParsedBody($data): ServerRequestInterface
    {
        $new = clone $this;
        $new->parsedBody = $data;
        return $new;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute($name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    public function withAttribute($name, $value): ServerRequestInterface
    {
        $new = clone $this;
        $new->attributes[$name] = $value;
        return $new;
    }

    public function withoutAttribute($name): ServerRequestInterface
    {
        $new = clone $this;
        unset($new->attributes[$name]);
        return $new;
    }

    public function getRequestTarget(): string
    {
        if (!$this->requestTarget) {
            $this->requestTarget = $this->uri !== null ? $this->uri->getPath() : '/';
        }
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

    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        $new = clone $this;
        $new->uri = $uri;

        if ($preserveHost && $this->hasHeader('Host')) {
            $new = $new->withHeader('Host', $this->getHeaderLine('Host'));
        } elseif (!$preserveHost) {
            $new = $new->withHeader('Host', [$uri->getHost()]);
        }

        return $new;
    }

    public function getUri(): UriInterface
    {
        return $this->server['REQUEST_URI'];
    }
}
