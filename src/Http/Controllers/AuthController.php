<?php

namespace Http\Controllers;

use Doctrine\ORM\EntityManagerInterface;
use Entities\User;
use Enums\UserRole;
use Http\Core\Request;
use Http\Core\Response;

class AuthController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function login(Request $request)
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

        $user->setAccessToken(sha1($email . $password));

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

        if ($email === null || $password === null) {
            return new Response(['error' => 'Email and password are required'], 400);
        }

        $user = new User();
        $user->setEmail($email);
        $user->setPasswordSha(sha1($password));
        $user->setRoles([$request->getRequest('role') ?? UserRole::ROLE_USER]);

        $user->setAccessToken(sha1($email . $password));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new Response([
            'accessToken' => $user->getAccessToken(),
        ], 201);
    }
}
