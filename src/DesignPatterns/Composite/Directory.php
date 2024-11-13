<?php

namespace DesignPatterns\Composite;

class Directory implements FileSystemEntityInterface
{
    private string $name;
    private array $children = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSize(): int
    {
        $totalSize = 0;
        foreach ($this->children as $child) {
            $totalSize += $child->getSize();
        }
        return $totalSize;
    }

    public function add(FileSystemEntityInterface $fileSystemEntity): void
    {
        $this->children[] = $fileSystemEntity;
    }

    public function remove(FileSystemEntityInterface $fileSystemEntity): void
    {
        foreach ($this->children as $key => $child) {
            if ($child === $fileSystemEntity) {
                unset($this->children[$key]);
            }
        }

        $this->children = array_values($this->children);
    }

    public function listContent(): array
    {
        return $this->children;
    }
}
