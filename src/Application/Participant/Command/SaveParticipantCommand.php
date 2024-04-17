<?php

declare(strict_types=1);

namespace App\Application\Participant\Command;

use App\Application\CommandInterface;
use App\Domain\Event\Event;

final class SaveParticipantCommand implements CommandInterface
{
    public ?string $firstName = null;
    public ?string $lastName = null;
    public ?string $email = null;

    public function __construct(
        public readonly Event $event,
    ) {
    }
}
