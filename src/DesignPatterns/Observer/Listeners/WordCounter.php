<?php

namespace DesignPatterns\Observer\Listeners;

class WordCounter implements TextScannerListenerInterface
{
    private int $count;

    public function __construct()
    {
        $this->count = 0;
    }
    
    public function execute(string $word): void
    {
        $this->count += 1;
    }

    public function getWordsCount(): int
    {
        return $this->count;
    }
}