<?php

namespace Http\Middlewares;

use Core\Container;
use Http\Core\Cookie\CookieManager;
use Http\Core\RequestInterface;
use Http\Core\Response;
use Http\Core\Session\SessionManager;
use Psr\Http\Message\ResponseInterface;

class CSRFMiddleware implements MiddlewareInterface
{
    public function __construct(
        protected SessionManager $sessionManager,
        protected CookieManager $cookieManager
    ) {
    }

    public function handle(Container $container, RequestInterface $request, callable $next): ResponseInterface
    {
        if ($request->getMethod() === 'POST') {
            $userToken = $request->getRequest('csrf');
            $sessionToken = $this->sessionManager->get('csrf_token');
            $cookieToken = $this->cookieManager->getCurrentUser();

            if (!$userToken || $userToken !== $cookieToken || $userToken !== $sessionToken) {
                return new Response(['error' => 'CSRF token mismatch'], 403);
            }
        }

        return $next($request);
    }
}
