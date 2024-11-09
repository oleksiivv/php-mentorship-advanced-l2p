<?php

namespace DesignPatterns\Visitor\Reports;

use DesignPatterns\Visitor\Company;

class NumberOfEmployeePerDepartmentReport implements VisitorInterface
{
    public function visitCompany(Company $company): array
    {
        $departmentsEmployeesCount = [];

        foreach ($company->getEmployees() as $employee) {
            $department = $employee->getDepartment();

            if (! isset($departmentsEmployeesCount[$department])) {
                $departmentsEmployeesCount[$department] = 0;
            }

            $departmentsEmployeesCount[$department] += 1;
        }

        return $departmentsEmployeesCount;
    }
}
