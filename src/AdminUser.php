<?php

declare(strict_types=1);

namespace App;

use App\Interfaces\Resettable;
use App\Traits\CanLogin;

final class AdminUser extends UserBase implements Resettable
{
    use CanLogin;

    private string $passwordHash;

    public function __construct(string $name, string $email)
    {
        parent::__construct($name, $email, self::ROLE_ADMIN);
        $this->passwordHash = password_hash('default', PASSWORD_BCRYPT);
    }

    public function resetPassword(string $newPassword): void
    {
        $this->passwordHash = password_hash($newPassword, PASSWORD_BCRYPT);
    }

    public function getAdminLogLabel(): string
    {
        return 'ADMIN-' . $this->getAuditTagForLogs();
    }
}
