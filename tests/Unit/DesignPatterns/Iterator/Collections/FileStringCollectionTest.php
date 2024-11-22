<?php

namespace Tests\Unit\DesignPatterns\Iterator\Collections;

use DesignPatterns\Iterator\Collections\FileStringCollection;
use DesignPatterns\Iterator\Iterators\StringIteratorInterface;
use PHPUnit\Framework\TestCase;
use Tests\FileTestCase;

class FileStringCollectionTest extends TestCase
{
    use FileTestCase;

    private string $fileContent;
    private string $fileName;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fileName = 'test.txt';
        $this->fileContent = 'first,second,third';

        $this->refreshFile($this->fileName);
        file_put_contents($this->fileName, $this->fileContent);
    }

    public function testCollectionInitializesWithFilePath(): void
    {
        $collection = new FileStringCollection($this->fileName);

        $this->assertInstanceOf(FileStringCollection::class, $collection);
    }

    public function testIteratorCanTraverseFileContent(): void
    {
        $collection = new FileStringCollection($this->fileName);

        $iterator = $collection->getIterator();

        $collectedStrings = [];
        while ($iterator->hasNext()) {
            $collectedStrings[] = $iterator->getNext();
        }

        $this->assertInstanceOf(StringIteratorInterface::class, $iterator);
        $this->assertSame($this->fileContent, implode(',', $collectedStrings));
    }
}
