<?php

namespace DesignPatterns\Strategy\Strategies;

use DesignPatterns\Visitor\Employee;

class EmployeeDepartmentSortStrategy implements EmployeeSortStrategyInterface
{
    public function __construct(private string $order = EmployeeSortStrategyInterface::SORT_ORDER_ASC)
    {
    }

    public function execute(array $employees): array
    {
        usort($employees, function (Employee $a, Employee $b) {
            return $this->order === EmployeeSortStrategyInterface::SORT_ORDER_ASC 
                ? strcmp($a->getDepartment(), $b->getDepartment())
                : strcmp($b->getDepartment(), $a->getDepartment());
        });

        return $employees;
    }
}