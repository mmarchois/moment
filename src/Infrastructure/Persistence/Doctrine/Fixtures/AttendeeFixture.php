<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Fixtures;

use App\Domain\Event\Attendee;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class AttendeeFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $attendee1 = new Attendee(
            'eb70ad27-77dc-4d4e-b066-cfb09d49d311',
            'Joan',
            'Francois',
            'joan.g.francois@gmail.com',
            'password1',
            $this->getReference('event'),
        );
        $attendee2 = new Attendee(
            'f5cbccb8-2748-4be5-ba47-7fb34181d2e4',
            'Francois',
            'Cardenas-Castro',
            'f.cardenas14@gmail.com',
            'password2',
            $this->getReference('event'),
        );

        $manager->persist($attendee1);
        $manager->persist($attendee2);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            EventFixture::class,
        ];
    }
}
