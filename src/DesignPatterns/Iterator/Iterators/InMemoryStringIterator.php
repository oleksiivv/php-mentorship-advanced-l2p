<?php

namespace DesignPatterns\Iterator\Iterators;

class InMemoryStringIterator implements StringIteratorInterface
{
    private array $strings;
    private int $currentIndex = 0;

    public function __construct(array $strings)
    {
        $this->strings = $strings;
    }

    public function hasNext(): bool
    {
        return $this->currentIndex < count($this->strings);
    }

    public function getNext(): string|null
    {
        if (! $this->hasNext()) {
            return null;
        }

        return $this->strings[$this->currentIndex++];
    }
}
