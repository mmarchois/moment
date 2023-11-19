<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository\Event;

use App\Domain\Event\Event;
use App\Domain\Event\Repository\EventRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

final class EventRepository extends ServiceEntityRepository implements EventRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
    ) {
        parent::__construct($registry, Event::class);
    }

    private const NB_ATTENDEE_QUERY = '
        SELECT count(DISTINCT a1.uuid)
        FROM App\Domain\Event\Attendee a1
        WHERE a1.event = e.uuid';

    public function findActiveEvents(
        int $pageSize,
        int $page,
        ?string $loggedUserUuid,
        bool $displayOnlyLoggedUserEvents = false,
    ): array {
        $qb = $this->createQueryBuilder('e')
            ->select('e.uuid, e.title, e.date')
            ->addSelect(sprintf('(%s) as nbAttendees', self::NB_ATTENDEE_QUERY))
            ->orderBy('e.date', 'DESC')
            ->setFirstResult($pageSize * ($page - 1))
            ->setMaxResults($pageSize);

        $query = $qb->getQuery();
        $paginator = new Paginator($query, false);
        $paginator->setUseOutputWalkers(false);

        $result = [
            'events' => [],
            'count' => $paginator->count(),
        ];

        foreach ($paginator as $event) {
            array_push($result['events'], $event);
        }

        return $result;
    }

    public function findDetailedEvent(string $uuid, ?string $loggedUserUuid): array
    {
        $qb = $this->createQueryBuilder('e')
            ->select('
                e.uuid,
                e.title,
                e.date,
            ')
            ->addSelect(sprintf('(%s) as nbAttendees', self::NB_ATTENDEE_QUERY))
            ->innerJoin('e.owner', 'o')
            ->where('e.uuid = :uuid')
            ->setParameter('uuid', $uuid)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    public function findOneByUuid(string $uuid): ?Event
    {
        return $this->createQueryBuilder('e')
            ->addSelect('o')
            ->where('e.published = true')
            ->andWhere('e.uuid = :uuid')
            ->setParameter('uuid', $uuid)
            ->innerJoin('e.owner', 'o')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
