<?php

namespace Tests\Unit\DesignPatterns\Strategy;

use DesignPatterns\Strategy\EmployeeCollection;
use DesignPatterns\Strategy\Strategies\EmployeeNameSortStrategy;
use DesignPatterns\Strategy\Strategies\EmployeeSalarySortStrategy;
use DesignPatterns\Strategy\Strategies\EmployeeSortStrategyInterface;
use DesignPatterns\Visitor\Employee;
use PHPUnit\Framework\TestCase;

class EmployeeCollectionTest extends TestCase
{
    /**
     * @dataProvider sortingDataProvider
     */
    public function test_collection_can_be_sorted(array $employees, EmployeeSortStrategyInterface $employeeSortStrategy, array $expectedResult): void
    {
        $collection = new EmployeeCollection($employees);
        $collection->setEmployeeSortStrategy($employeeSortStrategy);
        $collection->sort();

        $this->assertEquals($expectedResult, $collection->getEmployees());
    }

    public function sortingDataProvider(): array
    {
        return [
            'sort employees ascending by name' => [
                'employees' => [
                    new Employee('Joe', 30000, 'tech'),
                    new Employee('Jane', 50000, 'tech'),
                    new Employee('Peter', 40000, 'sales'),
                ],
                'employeeSortStrategy' => new EmployeeNameSortStrategy(EmployeeSortStrategyInterface::SORT_ORDER_ASC),
                'expectedResult' => [
                    new Employee('Jane', 50000, 'tech'),
                    new Employee('Joe', 30000, 'tech'),
                    new Employee('Peter', 40000, 'sales'),
                ],
            ],
            'sort employees descending by name' => [
                'employees' => [
                    new Employee('Joe', 30000, 'tech'),
                    new Employee('Jane', 50000, 'tech'),
                    new Employee('Peter', 40000, 'sales'),
                ],
                'employeeSortStrategy' => new EmployeeNameSortStrategy(EmployeeSortStrategyInterface::SORT_ORDER_DESC),
                'expectedResult' => [
                    new Employee('Peter', 40000, 'sales'),
                    new Employee('Joe', 30000, 'tech'),
                    new Employee('Jane', 50000, 'tech'),
                ],
            ],
            'sort employees ascending by salary' => [
                'employees' => [
                    new Employee('Joe', 30000, 'tech'),
                    new Employee('Jane', 50000, 'tech'),
                    new Employee('Peter', 40000, 'sales'),
                ],
                'employeeSortStrategy' => new EmployeeSalarySortStrategy(EmployeeSortStrategyInterface::SORT_ORDER_ASC),
                'expectedResult' => [
                    new Employee('Joe', 30000, 'tech'),
                    new Employee('Peter', 40000, 'sales'),
                    new Employee('Jane', 50000, 'tech'),
                ],
            ],
            'sort employees descending by salary' => [
                'employees' => [
                    new Employee('Joe', 30000, 'tech'),
                    new Employee('Jane', 50000, 'tech'),
                    new Employee('Peter', 40000, 'sales'),
                ],
                'employeeSortStrategy' => new EmployeeSalarySortStrategy(EmployeeSortStrategyInterface::SORT_ORDER_DESC),
                'expectedResult' => [
                    new Employee('Jane', 50000, 'tech'),
                    new Employee('Peter', 40000, 'sales'),
                    new Employee('Joe', 30000, 'tech'),
                ],
            ],
        ];
    }
}
