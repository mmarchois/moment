<?php

declare(strict_types=1);

namespace App\Domain\Participant\Repository;

use App\Domain\Event\Event;
use App\Domain\Participant\Participant;

interface ParticipantRepositoryInterface
{
    public function add(Participant $participant): Participant;

    public function findParticipantsByEvent(string $userUuid, string $eventUuid, int $pageSize, int $page): array;

    public function findOneByEventAndEmail(Event $event, string $email): ?Participant;
}