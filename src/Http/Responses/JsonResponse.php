<?php

namespace Http\Responses;

class JsonResponse
{
    private array $data;

    private int $status;

    public function __construct(array $data, int $status = 200)
    {
        $this->data = $data;
        $this->status = $status;
    }

    public function getContent(): string
    {
        return json_encode([
            'data' => $this->data,
        ]);
    }

    public function getStatusCode(): int
    {
        return $this->status;
    }
}
