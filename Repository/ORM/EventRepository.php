<?php
namespace Dende\CalendarBundle\Repository\ORM;

use Dende\Calendar\Application\Repository\EventRepositoryInterface;
use Dende\Calendar\Domain\Calendar\Event;
use Dende\Calendar\Domain\Calendar\Event\Occurrence;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class OccurrenceRepository.
 */
class EventRepository extends EntityRepository implements EventRepositoryInterface
{
    /**
     * @param Event $event
     */
    public function insert(Event $event)
    {
        $em = $this->getEntityManager();

        $em->persist($event);
        $em->flush();
    }

    public function update(Event $event)
    {
        $this->getEntityManager()->merge($event);
        $this->getEntityManager()->flush();
    }

    /**
     * @param $event
     */
    public function remove(Event $event)
    {
        $event->setDeletedAt(new \DateTime('now'));
        $em = $this->getEntityManager();
        $em->flush($event);
    }

    /**
     * It's not that stupid as it seems. Classes retrieved by relation are not always the same as overloaded types.
     *
     * @param Occurrence $occurrence
     *
     * @throws NonUniqueResultException
     *
     * @return Event|null
     */
    public function findOneByOccurrence(Occurrence $occurrence)
    {
        $qb = $this->createQueryBuilder('e');

        $result = $qb
            ->where('e.eventId = :eventId')
            ->setParameter('eventId', $occurrence->event()->id())
            ->getQuery()
            ->getOneOrNullResult();

        return $result;
    }

    public function findOneById(string $id)
    {
        return $this->findOneBy(['eventId' => $id]);
    }
}
