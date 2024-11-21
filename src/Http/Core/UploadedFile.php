<?php

namespace Http\Core;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use RuntimeException;

class UploadedFile implements UploadedFileInterface
{
    private mixed $size;
    private mixed $error;
    private mixed $clientFilename;
    private mixed $clientMediaType;
    private mixed $tmpFile;
    private bool $moved = false;

    public function __construct($file)
    {
        $this->size = $file['size'];
        $this->error = $file['error'];
        $this->clientFilename = $file['name'];
        $this->clientMediaType = $file['type'];
        $this->tmpFile = $file['tmp_name'];
    }

    public function getStream(): StreamInterface
    {
        if ($this->moved) {
            throw new RuntimeException('Cannot retrieve stream after it has been moved');
        }

        return new Stream(fopen($this->tmpFile, 'r'));
    }

    public function moveTo($targetPath): void
    {
        if ($this->moved) {
            throw new RuntimeException('Uploaded file has already been moved');
        }
        if (!move_uploaded_file($this->tmpFile, $targetPath)) {
            throw new RuntimeException('Failed to move uploaded file');
        }
        $this->moved = true;
    }

    public function getSize(): int|null
    {
        return $this->size;
    }

    public function getError(): int
    {
        return $this->error;
    }

    public function getClientFilename(): string|null
    {
        return $this->clientFilename;
    }

    public function getClientMediaType(): string|null
    {
        return $this->clientMediaType;
    }
}
