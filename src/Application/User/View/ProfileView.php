<?php

declare(strict_types=1);

namespace App\Application\User\View;

final readonly class ProfileView
{
    public function __construct(
        public string $username,
        public string $email,
    ) {
    }
}
