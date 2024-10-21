<?php

namespace DesignPatterns\Visitor\Reports;

use DesignPatterns\Visitor\Company;

class AverageSalaryReport implements VisitorInterface
{
    public function visitCompany(Company $company): array
    {
        $totalSalary = 0;
        $numberOfEmployees = count($company->getEmployees());

        foreach ($company->getEmployees() as $employee) {
            $totalSalary += $employee->getSalary();
        }

        return [
            'average salary' => $numberOfEmployees > 0 ? $totalSalary / $numberOfEmployees : 0,
        ];
    }
}
