<?php

namespace DesignPatterns\Iterator\Collections;

use DesignPatterns\Iterator\Iterators\StringIteratorInterface;
use DesignPatterns\Iterator\Iterators\FileStringIterator;

class FileStringCollection implements StringCollectionInterface
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function getIterator(): StringIteratorInterface
    {
        return new FileStringIterator($this->filePath);
    }
}
