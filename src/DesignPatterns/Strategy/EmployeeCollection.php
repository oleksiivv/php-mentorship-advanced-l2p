<?php

namespace DesignPatterns\Strategy;

use DesignPatterns\Strategy\Strategies\EmployeeSortStrategyInterface;
use DesignPatterns\Visitor\Employee;

class EmployeeCollection
{
    private EmployeeSortStrategyInterface $employeeSortStrategy;

    public function __construct(private array $employees)
    {
    }

    public function addEmployee(Employee $employee): void
    {
        $this->employees[] = $employee;
    }

    public function getEmployees(): array
    {
        return $this->employees;
    }

    public function setEmployeeSortStrategy(EmployeeSortStrategyInterface $employeeSortStrategy): void
    {
        $this->employeeSortStrategy = $employeeSortStrategy;
    }

    public function sort(): void
    {
        if ($this->employeeSortStrategy !== null) {
            $this->employees = $this->employeeSortStrategy->execute($this->employees);
        }
    }
}
