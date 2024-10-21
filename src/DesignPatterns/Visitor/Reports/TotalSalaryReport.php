<?php

namespace DesignPatterns\Visitor\Reports;

use DesignPatterns\Visitor\Company;

class TotalSalaryReport implements VisitorInterface
{
    public function visitCompany(Company $company): array
    {
        $totalSalary = 0;

        foreach ($company->getEmployees() as $employee) {
            $totalSalary += $employee->getSalary();
        }

        return [
            'total salary' => $totalSalary,
        ];
    }
}
