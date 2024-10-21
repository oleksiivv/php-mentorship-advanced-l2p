<?php

namespace DesignPatterns\Observer\Listeners;

class ReverseWordHandler implements TextScannerListenerInterface
{
    public array $reversedWords;

    public function __construct()
    {
        $this->reversedWords = [];
    }
    
    public function execute(string $word): void
    {
        $this->reversedWords[] = strrev($word);
    }

    public function getReversedWords(): array
    {
        return $this->reversedWords;
    }
}