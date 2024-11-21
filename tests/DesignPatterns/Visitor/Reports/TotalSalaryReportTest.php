<?php

namespace Tests\DesignPatterns\Visitor\Reports;

use DesignPatterns\Visitor\Employee;
use PHPUnit\Framework\TestCase;
use DesignPatterns\Visitor\Company;
use DesignPatterns\Visitor\Reports\TotalSalaryReport;

class TotalSalaryReportTest extends TestCase
{
    /**
     * @dataProvider providerTotalSalary
     */
    public function testTotalSalary(string $companyName, array $employees, array $expectedResult): void
    {
        $company = new Company($companyName, $employees);

        $totalSalaryReport = new TotalSalaryReport();

        $result = $company->accept($totalSalaryReport);

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
                    'total salary' => 120000,
                ],
            ],
            [
                'companyName' => 'Company1',
                'employees' => [
                    new Employee('Joe', 30000, 'tech'),
                ],
                'expectedResult' => [
                    'total salary' => 30000,
                ],
            ],
            [
                'companyName' => 'Company1',
                'employees' => [],
                'expectedResult' => [
                    'total salary' => 0,
                ],
            ],
        ];
    }
}
