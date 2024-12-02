<?php

namespace Http\Core\Requests;

use Entities\UserRole;
use Http\Core\HttpMessage;
use Http\Core\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class LoginRequest extends Request
{
    protected string|null $requiredRole = null;
}
