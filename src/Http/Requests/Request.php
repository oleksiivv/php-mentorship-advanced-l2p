<?php

namespace Http\Requests;

class Request
{
    private array $query;
    private array $request;
    private array $server;

    public function __construct()
    {
        $this->query = $_GET;
        $this->server = $_SERVER;

        $this->handleRequestData();
    }

    private function handleRequestData(): void
    {
        if ($this->getContentType() === 'application/json') {
            $jsonContent = file_get_contents('php://input');
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

    public function getQuery($key, $default = null): string|null
    {
        return $this->query[$key] ?? $default;
    }

    public function getRequest($key, $default = null): string|null
    {
        return $this->request[$key] ?? $default;
    }

    public function getMethod(): string
    {
        return $this->server['REQUEST_METHOD'];
    }

    public function getUri(): string
    {
        return $this->server['REQUEST_URI'];
    }
}
