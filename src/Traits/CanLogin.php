<?php

declare(strict_types=1);

namespace App\Traits;

trait CanLogin
{
    protected bool $loggedIn = false;

    public function login(string $email): bool
    {
        if ($email === $this->getEmail()) {
            $this->loggedIn = true;
        }

        return $this->loggedIn;
    }

    public function logout(): void
    {
        $this->loggedIn = false;
    }

    public function isLoggedIn(): bool
    {
        return $this->loggedIn;
    }
}
