<?php

namespace DesignPatterns\Visitor\Reports;

use DesignPatterns\Visitor\Company;

class TotalNumberOfEmployeesReport implements VisitorInterface
{
    public function visitCompany(Company $company): array
    {
        return [
            'total number of employees' => count($company->getEmployees()),
        ];
    }
}
