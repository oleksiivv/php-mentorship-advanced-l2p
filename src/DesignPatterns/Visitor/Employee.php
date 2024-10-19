<?php

namespace DesignPatterns\Visitor;

class Employee
{
    public function __construct(
        private string $name,
        private int $salary,
        private string $department,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSalary(): int
    {
        return $this->salary;
    }

    public function getDepartment(): string
    {
        return $this->department;
    }
}