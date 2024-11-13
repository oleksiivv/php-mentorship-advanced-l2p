<?php

namespace DesignPatterns\Observer\Listeners;

class LongestWordKeeper implements TextScannerListenerInterface
{
    private string|null $longestWord;

    public function __construct()
    {
        $this->longestWord = null;
    }

    public function execute(string $word): void
    {
        if ($this->longestWord === null) {
            $this->longestWord = $word;
            return;
        }

        if (strlen($word) > strlen($this->longestWord)) {
            $this->longestWord = $word;
        }
    }

    public function getLongestWord(): string|null
    {
        return $this->longestWord;
    }
}
