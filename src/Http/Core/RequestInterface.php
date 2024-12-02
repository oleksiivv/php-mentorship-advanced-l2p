<?php

namespace Http\Core;

use Psr\Http\Message\RequestInterface as BaseRequestInterface;

interface RequestInterface extends BaseRequestInterface
{
    public function validateAccess(array $requiredRoles): void;
}
