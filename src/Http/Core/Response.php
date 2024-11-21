<?php

namespace Http\Core;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response extends HttpMessage implements ResponseInterface
{
    protected array $data;
    protected int $status;
    protected string $reasonPhrase = '';

    public function __construct(array $data, int $status = 200)
    {
        $this->data = $data;
        $this->status = $status;
        $this->body = $this->createStreamFromData();
    }

    private function createStreamFromData(): StreamInterface
    {
        $stream = fopen('php://temp', 'r+');

        fwrite($stream, json_encode($this->data ?? []));
        rewind($stream);

        return new Stream($stream);
    }

    public function getStatusCode(): int
    {
        return $this->status;
    }

    public function withStatus($code, $reasonPhrase = ''): ResponseInterface
    {
        $new = clone $this;
        $new->status = $code;
        return $new;
    }

    public function getProtocolVersion(): string
    {
        return PROTOCOL_VERSION;
    }

    public function withProtocolVersion($version): ResponseInterface
    {
        $new = clone $this;
        $new->protocolVersion = $version;
        return $new;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader($name): bool
    {
        return isset($this->headers[$name]);
    }

    public function getHeader($name): array
    {
        return $this->headers[$name] ?? [];
    }

    public function getHeaderLine($name): string
    {
        return implode(",", $this->getHeader($name));
    }

    public function withHeader($name, $value): ResponseInterface
    {
        $new = clone $this;
        $new->headers[$name] = [$value];
        return $new;
    }

    public function withAddedHeader($name, $value): ResponseInterface
    {
        $new = clone $this;
        if (!isset($new->headers[$name])) {
            $new->headers[$name] = [];
        }
        $new->headers[$name][] = $value;
        return $new;
    }

    public function withoutHeader($name): ResponseInterface
    {
        $new = clone $this;
        unset($new->headers[$name]);
        return $new;
    }

    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body): ResponseInterface
    {
        $new = clone $this;
        $new->body = $body;
        return $new;
    }

    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    private function getDefaultReasonPhrase(int $status): string
    {
        $statusPhrases = [
            200 => 'OK',
            201 => 'Created',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error'
        ];

        return $statusPhrases[$status] ?? '';
    }
}
