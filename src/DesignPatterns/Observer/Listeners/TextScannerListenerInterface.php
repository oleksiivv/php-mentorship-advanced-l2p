<?php

namespace DesignPatterns\Observer\Listeners;

interface TextScannerListenerInterface
{
    public function execute(string $word): void;
}