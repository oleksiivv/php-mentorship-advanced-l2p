<?php

namespace DesignPatterns\Iterator\Iterators;

interface StringIteratorInterface
{
    public function hasNext(): bool;
    public function getNext(): string|null;
}