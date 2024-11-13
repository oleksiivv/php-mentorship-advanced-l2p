<?php

namespace DesignPatterns\Composite;

interface FileSystemEntityInterface
{
    public function getName(): string;
    public function getSize(): int;
}
