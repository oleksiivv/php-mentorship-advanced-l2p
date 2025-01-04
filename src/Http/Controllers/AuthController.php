<?php

namespace Http\Controllers;

use Doctrine\ORM\EntityManagerInterface;
use Entities\User;
use Enums\UserRole;
use Http\Core\Cookie\CookieManager;
use Http\Core\Request;
use Http\Core\Response;
use Http\Core\Session\SessionManager;

class AuthController
{
    private const ADMIN_EMAIL = 'admin@admin.test';

    public function __construct(
        private EntityManagerInterface $entityManager,
        private SessionManager         $sessionManager,
        private CookieManager          $cookieManager,
    ) {
    }

    public function login(Request $request)
    {
        $email = $request->getRequest('email');
        $password = $request->getRequest('password');

        if ($email === null || $password === null) {
            return new Response(['error' => 'Email and password are required'], 400);
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user || sha1($password) !== $user->getPasswordSha()) {
            return new Response(['error' => 'Invalid password'], 401);
        }

        $user->setAccessToken(sha1($email . $password));

        $this->handleSuccessfulLogin($user);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new Response([
            'accessToken' => $user->getAccessToken(),
        ], 200);
    }

    public function register(Request $request): Response
    {
        $email = $request->getRequest('email');
        $password = $request->getRequest('password');
        $name = $request->getRequest('name');

        if ($email === null || $password === null || $name === null) {
            return new Response(['error' => 'Email, password and name are required'], 400);
        }

        $user = new User();
        $user->setEmail($email);
        $user->setPasswordSha(sha1($password));
        $user->setName($name);

        if ($email === self::ADMIN_EMAIL) {
            $user->setRoles([UserRole::ROLE_ADMIN]);
        } else {
            $user->setRoles([$request->getRequest('role') ?? UserRole::ROLE_USER]);
        }

        $user->setAccessToken(sha1($email . $password));

        $this->handleSuccessfulLogin($user);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new Response([
            'accessToken' => $user->getAccessToken(),
        ], 201);
    }

    public function authPage(Request $request): Response
    {
        return new Response([], 200, 'text/html', 'auth.php');
    }

    private function handleSuccessfulLogin(User $user): void
    {
        $this->sessionManager->set($user->getAccessToken(), $user);
        $this->sessionManager->set('csrf_token', $user->getAccessToken());
        $this->cookieManager->setCurrentUser($user);
    }
}
