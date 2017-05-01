<?php
namespace Dende\CalendarBundle\Repository\ORM;

use DateTime;
use Dende\Calendar\Application\Repository\OccurrenceRepositoryInterface;
use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\Event;
use Dende\Calendar\Domain\Calendar\Event\Occurrence;
use Dende\Calendar\Domain\Calendar\Event\OccurrenceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

/**
 * Class OccurrenceRepository
 * @package Dende\CommonBundle\Repository\ORM
 */
class OccurrenceRepository extends EntityRepository implements OccurrenceRepositoryInterface
{
    /**
     * @param Calendar $calendar
     * @param DateTime $start
     * @param DateTime $end
     * @return array|ArrayCollection|Occurrence[]
     */
    public function findByCalendar(Calendar $calendar, DateTime $start, DateTime $end)
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $expr = $queryBuilder->expr();

        $queryBuilder
            ->innerJoin('o.event', 'e')
            ->where($expr->andX(
                $expr->gt("o.startDate", ':start'),
                $expr->lt("o.endDate", ':end'),
                $expr->eq('e.calendar', ':calendar')
            ))
            ->andWhere('o.deletedAt is NULL')
            ->orderBy("o.startDate", "ASC")
            ->setParameters([
                'calendar' => $calendar,
                'start' => $start,
                'end' => $end,
            ]);

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

    /**
     * @param DateTime $start
     * @param DateTime $end
     * @return array|ArrayCollection|Occurrence[]
     */
    public function findByPeriod(DateTime $start, DateTime $end)
    {
        $qb = $this->createQueryBuilder('o');

        $qb
            ->where('o.occurrenceData.startDate >= :start')
            ->andWhere('o.occurrenceData.endDate <= :end')
            ->orderBy('o.occurrenceData.startDate', 'ASC')
            ->setParameters([
                'start' => $start,
                'end' => $end,
            ]);

        $query = $qb->getQuery();

        return $query->getResult();
    }

    public function insert(OccurrenceInterface $occurrence)
    {
        $em = $this->getEntityManager();
        $em->persist($occurrence);
        $em->flush($occurrence);

    }

    public function insertCollection($occurrenceCollection)
    {
        $this->insert($occurrenceCollection);
    }

    public function findAllByEvent(Event $event) : ArrayCollection
    {
        // TODO: Implement findAllByEvent() method.
    }

    public function findOneByDateAndCalendar(DateTime $date, Calendar $calendar)
    {
        // TODO: Implement findOneByDateAndCalendar() method.
    }

    /**
     * @param OccurrenceInterface $occurrences
     * @throws \Exception
     */
    public function update(OccurrenceInterface $occurrence)
    {
        $em = $this->getEntityManager();

        $em->merge($occurrence);
        $em->flush($occurrence);
    }

    public function findAllByEventUnmodified(Event $event)
    {
        // TODO: Implement findAllByEventUnmodified() method.
    }

    /**
     * @param OccurrenceInterface $occurrence
     * @throws \Exception
     */
    public function remove(OccurrenceInterface $occurrence)
    {
        $em = $this->getEntityManager();
        $em->remove($occurrence);
        $em->flush($occurrence);
    }

    /**
     * @param Event $event
     */
    public function removeAllForEvent(Event $event)
    {
        $em = $this->getEntityManager();
        foreach($event->occurrences() as $occurrence) {
            $em->remove($occurrence);
        }
        $em->flush();
    }

    public function findByDateAndCalendar(DateTime $date, Calendar $calendar): ArrayCollection
    {
        // TODO: Implement findByDateAndCalendar() method.
    }

    public function findOneById(string $id) : OccurrenceInterface
    {
        return $this->findOneBy(['occurrenceId.id' => $id]);
    }

    public function findAll() : ArrayCollection
    {
        return new ArrayCollection(parent::findAll());
    }
}
