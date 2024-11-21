<?php

namespace Http\Core;

use Exception;
use InvalidArgumentException;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

class Stream implements StreamInterface
{
    private $stream;

    public function __construct($stream)
    {
        if (is_resource($stream)) {
            $this->stream = $stream;
        } else {
            throw new InvalidArgumentException('Stream must be a valid resource');
        }
    }

    public function __toString(): string
    {
        try {
            if (fseek($this->stream, 0) === -1) {
                return '';
            }
            return stream_get_contents($this->stream);
        } catch (Exception) {
            return '';
        }
    }

    public function close(): void
    {
        fclose($this->stream);
    }

    public function detach()
    {
        $detached = $this->stream;
        $this->stream = null;
        return $detached;
    }

    public function getSize(): ?int
    {
        $stats = fstat($this->stream);
        return $stats['size'] ?? null;
    }

    public function tell(): int
    {
        $result = ftell($this->stream);
        if ($result === false) {
            throw new RuntimeException('Unable to determine the position of the pointer in the stream');
        }
        return $result;
    }

    public function eof(): bool
    {
        return feof($this->stream);
    }

    public function isSeekable(): bool
    {
        $meta = stream_get_meta_data($this->stream);
        return $meta['seekable'];
    }

    public function seek($offset, $whence = SEEK_SET): void
    {
        if (!$this->isSeekable() || fseek($this->stream, $offset, $whence) === -1) {
            throw new RuntimeException('Stream is not seekable or position cannot be set');
        }
    }

    public function rewind(): void
    {
        $this->seek(0);
    }

    public function isWritable(): bool
    {
        $meta = stream_get_meta_data($this->stream);
        return in_array($meta['mode'], ['w+', 'r+', 'x+', 'c+']);
    }

    public function write($string): int
    {
        $result = fwrite($this->stream, $string);
        if ($result === false) {
            throw new RuntimeException('Unable to write to the stream');
        }
        return $result;
    }

    public function isReadable(): bool
    {
        $meta = stream_get_meta_data($this->stream);
        return in_array($meta['mode'], ['r', 'r+', 'w+', 'x+', 'c+', 'rb', 'w+b', 'x+b', 'c+b', 'rb+']);
    }

    public function read($length): string
    {
        $result = fread($this->stream, $length);
        if ($result === false) {
            throw new RuntimeException('Unable to read from the stream');
        }
        return $result;
    }

    public function getContents(): string
    {
        $contents = stream_get_contents($this->stream);
        if ($contents === false) {
            throw new RuntimeException('Unable to read the remaining contents of the stream');
        }
        return $contents;
    }

    public function getMetadata($key = null)
    {
        $meta = stream_get_meta_data($this->stream);
        if (is_null($key)) {
            return $meta;
        }
        return $meta[$key] ?? null;
    }
}
