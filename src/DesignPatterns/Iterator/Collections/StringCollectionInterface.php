<?php

namespace DesignPatterns\Iterator\Collections;

use DesignPatterns\Iterator\Iterators\StringIteratorInterface;

interface StringCollectionInterface
{
    public function getIterator(): StringIteratorInterface;
}
