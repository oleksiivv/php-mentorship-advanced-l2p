<?php

namespace DesignPatterns\Iterator\Iterators;

class FileStringIterator implements StringIteratorInterface
{
    private mixed $fileHandle;
    private string|null $nextLine = null;

    public function __construct(string $filePath)
    {
        $this->fileHandle = fopen($filePath, 'r');
        $this->nextLine = $this->readLine();
    }

    private function readLine(): string|null
    {
        if (feof($this->fileHandle)) {
            fclose($this->fileHandle);
            return null;
        }

        return fgets($this->fileHandle);
    }

    public function hasNext(): bool
    {
        return $this->nextLine !== null;
    }

    public function getNext(): string|null
    {
        $currentLine = $this->nextLine;

        $this->nextLine = $this->readLine();

        return $currentLine;
    }
}
