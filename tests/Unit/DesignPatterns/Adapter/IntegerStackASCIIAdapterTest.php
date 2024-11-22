<?php

namespace Tests\Unit\DesignPatterns\Adapter;

use DesignPatterns\Adapter\IntegerStack;
use DesignPatterns\Adapter\IntegerStackASCIIAdapter;
use PHPUnit\Framework\TestCase;

class IntegerStackASCIIAdapterTest extends TestCase
{
    public function test_push_and_pop_works_correctly(): void
    {
        $integerStack = new IntegerStack();
        $asciiStackAdapter = new IntegerStackASCIIAdapter($integerStack);

        $firstElement = 'A';
        $secondElement = 'B';

        $asciiStackAdapter->push($firstElement);
        $asciiStackAdapter->push($secondElement);

        $this->assertSame($secondElement, $asciiStackAdapter->pop());
        $this->assertSame($firstElement, $asciiStackAdapter->pop());
    }
}
