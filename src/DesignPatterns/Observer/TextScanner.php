<?php

namespace DesignPatterns\Observer;

use DesignPatterns\Observer\Listeners\TextScannerListenerInterface;
use Exception;

class TextScanner
{
    private array $listeners;

    public function __construct()
    {
        $this->listeners = [];
    }

    public function addListener(TextScannerListenerInterface $textScannerListener): void
    {
        $this->listeners[] = $textScannerListener;
    }

    public function execute(string $filename): void
    {
        $handle = fopen($filename, 'r');
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $words = preg_split('/\s+/', $line, -1, PREG_SPLIT_NO_EMPTY);
                foreach ($words as $word) {
                    $this->notifyListeners($word);
                }
            }

            fclose($handle);
        } else {
            throw new Exception('Unable to open file: $filename');
        }
    }

    private function notifyListeners(string $word)
    {
        foreach ($this->listeners as $listener) {
            $listener->execute($word);
        }
    }
}
