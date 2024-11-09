<?php

namespace DesignPatterns\Iterator\Collections;

use DesignPatterns\Iterator\Iterators\StringIteratorInterface;
use DesignPatterns\Iterator\Iterators\InMemoryStringIterator;

class InMemoryStringCollection implements StringCollectionInterface
{
    private array $strings;

    public function __construct(array $strings) {
        $this->strings = $strings;
    }

    public function getIterator(): StringIteratorInterface
    {
        return new InMemoryStringIterator($this->strings);
    }
}