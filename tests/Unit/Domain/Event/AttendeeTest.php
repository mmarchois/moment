<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Event;

use App\Domain\Event\Attendee;
use App\Domain\Event\Event;
use PHPUnit\Framework\TestCase;

final class AttendeeTest extends TestCase
{
    public function testGetters(): void
    {
        $event = $this->createMock(Event::class);

        $attendee = new Attendee(
            '9cebe00d-04d8-48da-89b1-059f6b7bfe44',
            'Mathieu',
            'Marchois',
            'mathieu@fairness.coop',
            'myVoucher',
            $event,
        );

        $this->assertSame('9cebe00d-04d8-48da-89b1-059f6b7bfe44', $attendee->getUuid());
        $this->assertSame($event, $attendee->getEvent());
        $this->assertSame('Mathieu', $attendee->getFirstName());
        $this->assertSame('Marchois', $attendee->getLastName());
        $this->assertSame('mathieu@fairness.coop', $attendee->getEmail());
        $this->assertSame('myVoucher', $attendee->getPassword());
    }
}
