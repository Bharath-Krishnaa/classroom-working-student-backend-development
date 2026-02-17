<?php

declare(strict_types=1);

namespace App;

use App\Traits\CanLogin;

final class CustomerUser extends UserBase
{
    use CanLogin;

    private int $loyaltyPoints = 0;

    public function __construct(string $name, string $email, int $points = 0)
    {
        parent::__construct($name, $email, self::ROLE_CUSTOMER);
        $this->loyaltyPoints = max(0, $points);
    }

    public function addPoints(int $points): void
    {
        if ($points > 0) {
            $this->loyaltyPoints += $points;
        }
    }

    public function getPoints(): int
    {
        return $this->loyaltyPoints;
    }
}
