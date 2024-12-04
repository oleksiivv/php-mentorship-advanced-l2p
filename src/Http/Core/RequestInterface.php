<?php

namespace Http\Core;

use Http\Core\Session\SessionManager;
use Psr\Http\Message\RequestInterface as BaseRequestInterface;

interface RequestInterface extends BaseRequestInterface
{
    public function addMiddlewares(array $middlewares): void;

    public function getMiddlewares(): array;

    public function setSessionManager(SessionManager $sessionManager);

    public function getSessionData($key);

    public function setSessionData($key, $value);

    public function getCsrfToken();

    public function setCsrfToken($token);
}
