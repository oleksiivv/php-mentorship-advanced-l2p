<?php

namespace DesignPatterns\Observer\Listeners;

class NumberCounter implements TextScannerListenerInterface
{
    private int $count;

    public function __construct()
    {
        $this->count = 0;
    }

    public function execute(string $word): void
    {
        if (is_numeric($word)) {
            $this->count += 1;
        }
    }

    public function getNumbersCount(): int
    {
        return $this->count;
    }
}
