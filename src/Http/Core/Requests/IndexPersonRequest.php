<?php

namespace Http\Core\Requests;

use Entities\UserRole;
use Http\Core\HttpMessage;
use Http\Core\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class IndexPersonRequest extends Request
{
    protected string|null $requiredRole = UserRole::ROLE_USER;
}
