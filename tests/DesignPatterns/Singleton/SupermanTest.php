<?php

namespace Tests\DesignPatterns\Singleton;

use PHPUnit\Framework\TestCase;
use DesignPatterns\Singleton\Superman;
use Error;
use Exception;

class SupermanTest extends TestCase
{
    public function testSingletonReturnsSameInstance(): void
    {
        $superman1 = Superman::getInstance();
        
        $superman2 = Superman::getInstance();
        
        $this->assertSame($superman1, $superman2);
    }

    public function testFlyMethodWorks(): void
    {
        $superman = Superman::getInstance();
        
        $this->assertEquals('Superman is flying', $superman->fly());
    }

    public function testCannotCloneSingleton(): void
    {
        $superman = Superman::getInstance();

        $this->expectException(Error::class);

        clone $superman;
    }

    public function testCannotUnserializeSingleton(): void
    {
        $superman = Superman::getInstance();

        $this->expectException(Exception::class);
        //$this->expectExceptionMessage('Cannot unserialize singleton');

        unserialize(serialize($superman));
    }
}
