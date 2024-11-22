<?php

namespace Tests\Unit\DesignPatterns\Composite;

use DesignPatterns\Composite\Directory;
use DesignPatterns\Composite\File;
use PHPUnit\Framework\TestCase;

class DirectoryTest extends TestCase
{
    public function testAdd(): void
    {
        $directory = new Directory('MainDirectory');

        $file = new File('TestFile1.txt', 100);

        $subDirectory = new Directory('SubDirectory');
        $subDirectoryFile = new File('TestFile2.txt', 300);

        $directory->add($file);
        $subDirectory->add($subDirectoryFile);
        $directory->add($subDirectory);

        $content = $directory->listContent();

        $this->assertCount(2, $content);
        $this->assertSame($file, $content[0]);
        $this->assertSame($subDirectory, $content[1]);
        $this->assertSame($subDirectory->listContent()[0], $subDirectoryFile);
    }

    public function testRemove(): void
    {
        $directory = new Directory('MainDirectory');

        $file1 = new File('TestFile1.txt', 100);
        $file2 = new File('TestFile2.txt', 200);

        $directory->add($file1);
        $directory->add($file2);

        $directory->remove($file1);
        $content = $directory->listContent();

        $this->assertCount(1, $content);
        $this->assertSame($file2, $content[0]);
    }

    public function testGetSize(): void
    {
        $directory = new Directory('MainDirectory');

        $file1 = new File('TestFile1.txt', 100);
        $file2 = new File('TestFile2.txt', 200);

        $directory->add($file1);
        $directory->add($file2);

        $size = $directory->getSize();

        $this->assertEquals(300, $size);
    }
}
