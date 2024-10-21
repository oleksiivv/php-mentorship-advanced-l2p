<?php

namespace DesignPatterns\Singleton;

use Exception;

class Superman
{
    private static Superman|null $instance = null;

    private function __construct()
    {
    }

    private function __clone(): void
    {
    }

    public function __wakeup(): void
    {
        throw new Exception('Cannot unserialize singleton');
    }

    public static function getInstance(): Superman
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function fly(): string
    {
        return 'Superman is flying';
    }
}