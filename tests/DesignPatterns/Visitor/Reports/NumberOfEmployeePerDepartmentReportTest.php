<?php

namespace Tests\DesignPatterns\Visitor\Reports;

use DesignPatterns\Visitor\Employee;
use PHPUnit\Framework\TestCase;
use DesignPatterns\Visitor\Company;
use DesignPatterns\Visitor\Reports\NumberOfEmployeePerDepartmentReport;

class NumberOfEmployeePerDepartmentReportTest extends TestCase
{
    /**
     * @dataProvider providerTotalSalary
     */
    public function testTotalSalary(string $companyName, array $employees, array $expectedResult): void
    {
        $company = new Company($companyName, $employees);

        $numberOfEmployeePerDepartmentReport = new NumberOfEmployeePerDepartmentReport();

        $result = $company->accept($numberOfEmployeePerDepartmentReport);

        $this->assertEquals($expectedResult, $result);
    }

    public function providerTotalSalary(): array
    {
        return [
            [
                'companyName' => 'Company1',
                'employees' => [
                    new Employee('Joe', 30000, 'tech'),
                    new Employee('Jane', 50000, 'tech'),
                    new Employee('Peter', 40000, 'sales'),
                    new Employee('Tony', 30000, 'management'),
                    new Employee('Steve', 50000, 'management'),
                ],
                'expectedResult' => [
                    'tech' => 2,
                    'sales' => 1,
                    'management' => 2,
                ],
            ],
            [
                'companyName' => 'Company1',
                'employees' => [
                    new Employee('Joe', 30000, 'tech'),
                ],
                'expectedResult' => [
                    'tech' => 1,
                ],
            ],
            [
                'companyName' => 'Company1',
                'employees' => [],
                'expectedResult' => [],
            ],
        ];
    }
}