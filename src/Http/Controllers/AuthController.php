<?php

namespace Http\Controllers;

use DesignPatterns\AbstractFactory\PersonRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Entities\Person;
use Entities\User;
use Entities\UserRole;
use Http\Core\Request;
use Http\Core\Requests\LoginRequest;
use Http\Core\Requests\RegisterRequest;
use Http\Core\Response;

class AuthController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function login(LoginRequest $request)
    {
        $email = $request->getRequest('email');
        $password = $request->getRequest('password');

        if ($email === null || $password === null) {
            return new Response(['error' => 'Email and password are required'], 400);
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (sha1($password) !== $user->getPasswordSha()) {
            return new Response(['error' => 'Invalid password'], 401);
        }

        $accessToken = bin2hex(random_bytes(32));
        $user->setAccessToken($accessToken);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new Response([
            'accessToken' => $accessToken,
        ], 200);
    }

    public function register(RegisterRequest $request): Response
    {
        $email = $request->getRequest('email');
        $password = $request->getRequest('password');

        if ($email === null || $password === null) {
            return new Response(['error' => 'Email and password are required'], 400);
        }

        $user = new User();
        $user->setEmail($email);
        $user->setPasswordSha(sha1($password));
        $user->setRoles([$request->getRequest('role') ?? UserRole::ROLE_USER]);

        $accessToken = bin2hex(json_encode([
            'token' => random_bytes(32),
            'roles' => $user->getRoles(),
            'expiresAt' => $user->getAccessTokenExpiresAt()->format('Y-m-d H:i:s'),
        ]));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new Response([
            'accessToken' => $accessToken,
        ], 201);
    }
}
