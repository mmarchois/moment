<?php

declare(strict_types=1);

namespace App\Test\Unit\Infrastructure\Security;

use App\Domain\User\User;
use App\Infrastructure\Security\SymfonyUser;
use PHPUnit\Framework\TestCase;

final class SymfonyUserTest extends TestCase
{
    public function testUser()
    {
        $user = $this->createMock(User::class);
        $user
            ->expects(self::once())
            ->method('getUuid')
            ->willReturn('2d3724f1-2910-48b4-ba56-81796f6e100b');
        $user
            ->expects(self::once())
            ->method('getEmail')
            ->willReturn('mathieu@fairness.coop');
        $user
            ->expects(self::once())
            ->method('getFirstName')
            ->willReturn('Mathieu');
        $user
            ->expects(self::once())
            ->method('getLastName')
            ->willReturn('MARCHOIS');
        $user
            ->expects(self::once())
            ->method('getPassword')
            ->willReturn('password');
        $user
            ->expects(self::once())
            ->method('isVerified')
            ->willReturn(false);

        $symfonyUser = new SymfonyUser($user);

        $this->assertSame('2d3724f1-2910-48b4-ba56-81796f6e100b', $symfonyUser->getUuid());
        $this->assertSame(['ROLE_ADMIN'], $symfonyUser->getRoles());
        $this->assertSame('Mathieu', $symfonyUser->getFirstName());
        $this->assertSame('MARCHOIS', $symfonyUser->getLastName());
        $this->assertSame(null, $symfonyUser->getSalt());
        $this->assertSame('mathieu@fairness.coop', $symfonyUser->getUsername());
        $this->assertSame('mathieu@fairness.coop', $symfonyUser->getUserIdentifier());
        $this->assertSame('password', $symfonyUser->getPassword());
        $this->assertEmpty($symfonyUser->eraseCredentials());
        $this->assertFalse($symfonyUser->isVerified());
    }
}
