<?php

namespace DesignPatterns\Visitor;

use DesignPatterns\Visitor\Reports\VisitorInterface;

class Company
{
    public function __construct(
        private string $name,
        private array $employees,
    ) {
    }

    public function addEmployee(Employee $employee): void
    {
        $this->employees[] = $employee;
    }

    public function getEmployees(): array
    {
        return $this->employees;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function accept(VisitorInterface $visitor): array   
    {
        return $visitor->visitCompany($this);
    }
}