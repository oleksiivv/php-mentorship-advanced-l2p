<?php

namespace Tests\Unit\DesignPatterns\Iterator\Collections;

use DesignPatterns\Iterator\Collections\InMemoryStringCollection;
use DesignPatterns\Iterator\Iterators\StringIteratorInterface;
use PHPUnit\Framework\TestCase;

class InMemoryStringCollectionTest extends TestCase
{
    private array $strings;
    private InMemoryStringCollection $collection;

    protected function setUp(): void
    {
        parent::setUp();

        $this->strings = ['first', 'second', 'third'];

        $this->collection = new InMemoryStringCollection($this->strings);
    }

    public function testCollectionInitializesWithStrings(): void
    {
        $this->assertInstanceOf(InMemoryStringCollection::class, $this->collection);
    }

    public function testIteratorCanTraverseCollection(): void
    {
        $iterator = $this->collection->getIterator();

        $collectedStrings = [];

        while ($iterator->hasNext()) {
            $collectedStrings[] = $iterator->getNext();
        }

        $this->assertInstanceOf(StringIteratorInterface::class, $iterator);
        $this->assertSame($this->strings, $collectedStrings);
    }
}
