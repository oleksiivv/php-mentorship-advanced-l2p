<?php

namespace Http\Middlewares;

use Core\Container;
use Doctrine\ORM\EntityManagerInterface;
use Entities\User;
use Enums\UserRole;
use Http\Core\RequestInterface;
use Http\Core\Response;
use Psr\Http\Message\ResponseInterface;

class AuthenticationMiddleware implements MiddlewareInterface
{
    public function __construct(private array $requiredRoles)
    {
    }

    public function handle(Container $container, RequestInterface $request, callable $next): ResponseInterface
    {
        if (!$this->checkAccess($container, $request)) {
            return new Response(['error' => 'Not Authorized'], 401);
        }

        return $next($request);
    }

    public function checkAccess(Container $container, RequestInterface $request): bool
    {
        if ($this->requiredRoles == [UserRole::ROLE_GUEST] || empty($this->requiredRoles)) {
            return true;
        }

        $accessToken = $request->getHeader('Authorization')[0];

        if ($accessToken === null) {
            return false;
        }

        $user = $container->get(EntityManagerInterface::class)
            ->getRepository(User::class)
            ->findOneBy(['accessToken' => str_replace('Bearer ', '', $accessToken)]);

        $roles = $user?->getRoles() ?? [];

        $hasRequiredRole = false;
        foreach ($this->requiredRoles as $requiredRole) {
            if (in_array($requiredRole, $roles)) {
                $hasRequiredRole = true;
                break;
            }
        }

        if ($hasRequiredRole) {
            $request->setUser($user);

            return true;
        }

        return false;
    }
}
