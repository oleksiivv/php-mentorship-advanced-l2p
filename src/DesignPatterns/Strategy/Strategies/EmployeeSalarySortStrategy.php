<?php

namespace DesignPatterns\Strategy\Strategies;

use DesignPatterns\Visitor\Employee;

class EmployeeSalarySortStrategy implements EmployeeSortStrategyInterface
{
    public function __construct(private string $order = EmployeeSortStrategyInterface::SORT_ORDER_ASC)
    {
    }

    public function execute(array $employees): array
    {
        usort($employees, function (Employee $a, Employee $b) {
            return $this->order === EmployeeSortStrategyInterface::SORT_ORDER_ASC
                ? $a->getSalary() <=> $b->getSalary()
                : $b->getSalary() <=> $a->getSalary();
        });

        return $employees;
    }
}
