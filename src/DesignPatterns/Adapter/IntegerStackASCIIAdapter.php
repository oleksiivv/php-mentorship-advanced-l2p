<?php

namespace DesignPatterns\Adapter;

class IntegerStackASCIIAdapter implements ASCIIStackInterface
{
    private IntegerStackInterface $integerStack;

    public function __construct(IntegerStackInterface $integerStack)
    {
        $this->integerStack = $integerStack;
    }

    public function push(string $char): void
    {
        $asciiValue = ord($char);
        $this->integerStack->push($asciiValue);
    }

    public function pop(): string|null
    {
        try {
            $asciiValue = $this->integerStack->pop();
            return chr($asciiValue);
        } catch (RuntimeException $e) {
            return null;
        }
    }
}
