<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;

abstract class UserBase
{
    public const ROLE_ADMIN = 'admin';
    public const ROLE_CUSTOMER = 'customer';

    private string $name;
    protected string $email;
    public string $role;

    protected static int $instanceCount = 0;

    protected array $extra = [];

    public function __construct(string $name, string $email, string $role)
    {
        $this->setName($name);
        $this->setEmail($email);
        $this->role = $role;

        self::$instanceCount++;
    }

    public function __toString(): string
    {
        return sprintf('%s (%s) [%s]', $this->getName(), $this->getEmail(), $this->role);
    }

    public function __get(string $key): mixed
    {
        return $this->extra[$key] ?? null;
    }

    public function __set(string $key, mixed $value): void
    {
        $this->extra[$key] = $value;
    }

    public static function getInstanceCount(): int
    {
        return self::$instanceCount;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $name = trim($name);
        if ($name === '') {
            throw new InvalidArgumentException('Name cannot be empty.');
        }
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $email = trim($email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email: ' . $email);
        }

        $this->email = $email;
    }

    private function internalAuditTag(): string
    {
        return 'AUDIT-' . md5($this->email);
    }

    protected function getAuditTagForLogs(): string
    {
        return $this->internalAuditTag();
    }
}
