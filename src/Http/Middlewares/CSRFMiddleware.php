<?php

namespace Http\Middlewares;

use Core\Container;
use Http\Core\RequestInterface;
use Http\Core\Response;
use Http\Core\Session\SessionManager;
use Psr\Http\Message\ResponseInterface;

class CSRFMiddleware implements MiddlewareInterface
{
    public function __construct(protected SessionManager $sessionManager)
    {
    }

    public function handle(Container $container, RequestInterface $request, callable $next): ResponseInterface
    {
        if ($request->getMethod() === 'POST') {
            $userToken = $request->getHeader('X-CSRF-Token')[0];
            $sessionToken = $this->sessionManager->get('csrf_token');

            if (!$userToken || $userToken !== $sessionToken) {
                return new Response(['error' => 'CSRF token mismatch'], 403);
            }
        }

        $newToken = bin2hex(random_bytes(32));
        $this->sessionManager->set('csrf_token', $newToken);

        $request->setCsrfToken($newToken);

        return $next($request);
    }
}