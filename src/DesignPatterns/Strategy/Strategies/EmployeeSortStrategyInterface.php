<?php

namespace DesignPatterns\Strategy\Strategies;

interface EmployeeSortStrategyInterface
{
    public const SORT_ORDER_ASC = 'ASC';
    public const SORT_ORDER_DESC = 'DESC';

    public function execute(array $employees): array;
}