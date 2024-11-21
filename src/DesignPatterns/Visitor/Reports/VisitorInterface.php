<?php

namespace DesignPatterns\Visitor\Reports;

use DesignPatterns\Visitor\Company;

interface VisitorInterface
{
    public function visitCompany(Company $company): array;
}
