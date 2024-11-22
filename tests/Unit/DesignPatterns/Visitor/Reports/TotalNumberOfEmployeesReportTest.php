<?php

namespace Tests\Unit\DesignPatterns\Visitor\Reports;

use DesignPatterns\Visitor\Company;
use DesignPatterns\Visitor\Employee;
use DesignPatterns\Visitor\Reports\TotalNumberOfEmployeesReport;
use PHPUnit\Framework\TestCase;

class TotalNumberOfEmployeesReportTest extends TestCase
{
    /**
     * @dataProvider providerTotalSalary
     */
    public function testTotalSalary(string $companyName, array $employees, array $expectedResult): void
    {
        $company = new Company($companyName, $employees);

        $totalNumberOfEmployeesReport = new TotalNumberOfEmployeesReport();

        $result = $company->accept($totalNumberOfEmployeesReport);

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
                ],
                'expectedResult' => [
                    'total number of employees' => 3,
                ],
            ],
            [
                'companyName' => 'Company1',
                'employees' => [
                    new Employee('Joe', 30000, 'tech'),
                ],
                'expectedResult' => [
                    'total number of employees' => 1,
                ],
            ],
            [
                'companyName' => 'Company1',
                'employees' => [],
                'expectedResult' => [
                    'total number of employees' => 0,
                ],
            ],
        ];
    }
}
