<?php

namespace Tests\Unit\DesignPatterns\Visitor\Reports;

use DesignPatterns\Visitor\Company;
use DesignPatterns\Visitor\Employee;
use DesignPatterns\Visitor\Reports\AverageSalaryReport;
use PHPUnit\Framework\TestCase;

class AverageSalaryReportTest extends TestCase
{
    /**
     * @dataProvider providerAverageSalary
     */
    public function testAverageSalary(string $companyName, array $employees, array $expectedResult): void
    {
        $company = new Company($companyName, $employees);

        $averageSalaryReport = new AverageSalaryReport();

        $result = $company->accept($averageSalaryReport);

        $this->assertEquals($expectedResult, $result);
    }

    public function providerAverageSalary(): array
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
                    'average salary' => 40000,
                ],
            ],
            [
                'companyName' => 'Company1',
                'employees' => [
                    new Employee('Joe', 40000, 'tech'),
                    new Employee('Jane', 50000, 'tech'),
                    new Employee('Peter', 90000, 'sales'),
                ],
                'expectedResult' => [
                    'average salary' => 60000,
                ],
            ],
            [
                'companyName' => 'Company1',
                'employees' => [],
                'expectedResult' => [
                    'average salary' => 0,
                ],
            ],
        ];
    }
}
