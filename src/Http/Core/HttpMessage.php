<?php

namespace Http\Core;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

class HttpMessage implements MessageInterface
{
    protected array $headers = [];
    protected StreamInterface $body;

    protected string $protocolVersion;

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion(string $version): MessageInterface
    {
        $new = clone $this;
        $new->protocolVersion = $version;
        return $new;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader(string $name): bool
    {
        return isset($this->headers[strtolower($name)]);
    }

    public function getHeader(string $name): array
    {
        return $this->headers[strtolower($name)] ?? [];
    }

    public function getHeaderLine(string $name): string
    {
        if (!$this->hasHeader($name)) {
            return '';
        }
        return implode(', ', $this->getHeader($name));
    }

    public function withHeader($name, $value): MessageInterface
    {
        $new = clone $this;
        $new->headers[strtolower($name)] = is_array($value) ? $value : [$value];
        return $new;
    }

    public function withAddedHeader($name, $value): MessageInterface
    {
        $new = clone $this;
        if ($new->hasHeader($name)) {
            $new->headers[strtolower($name)][] = $value;
        } else {
            $new->headers[strtolower($name)] = [$value];
        }
        return $new;
    }

    public function withoutHeader($name): MessageInterface
    {
        $new = clone $this;
        if ($new->hasHeader($name)) {
            unset($new->headers[strtolower($name)]);
        }
        return $new;
    }

    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        $new = clone $this;
        $new->body = $body;
        return $new;
    }
}
