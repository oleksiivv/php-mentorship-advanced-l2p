<?php

namespace Http\Core\Cookie;

use Entities\User;

class CookieManager
{
    public function getCurrentUser(): ?string
    {
        return $_COOKIE['user'] ?? null;
    }

    public function setCurrentUser(User $user): void
    {
        $this->set('user', $user->getAccessToken());
    }

    public function set(string $key, string $value, int $expire = 0, string $path = '/', string $domain = '', bool $secure = false, bool $httpOnly = false): void
    {
        setcookie($key, $value, $expire, $path, $domain, $secure, $httpOnly);
    }

    public function get(string $key): ?string
    {
        return $_COOKIE[$key] ?? null;
    }

    public function remove(string $key): void
    {
        setcookie($key, '', time() - 3600);
    }
}
