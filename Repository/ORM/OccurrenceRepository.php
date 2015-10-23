<?php
namespace Dende\CalendarBundle\Repository\ORM;

use DateInterval;
use DateTime;
use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\Event;
use Dende\Calendar\Domain\Calendar\Event\Occurrence;
use Dende\Calendar\Domain\Repository\OccurrenceRepositoryInterface;
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
            ->setParameters([
                'calendar' => $calendar,
                'start' => $start,
                'end' => $end->add(new DateInterval('P1D')), // so we include last day of period also
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
        $queryBuilder = $this->createQueryBuilder('o');
        $expr = $queryBuilder->expr();

        $queryBuilder
            ->innerJoin('o.event', 'e')
            ->where($expr->andX(
                $expr->gt("o.startDate", ':start'),
                $expr->lt("o.endDate", ':end')
            ))
            ->setParameters([
                'start' => $start,
                'end' => $end->add(new DateInterval('P1D')), // so we include last day of period also
            ]);

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

    public function insert(Occurrence $occurrence)
    {
        // TODO: Implement insert() method.
    }

    public function findAllByEvent(Event $event)
    {
        // TODO: Implement findAllByEvent() method.
    }

    public function findOneByDateAndCalendar(DateTime $date, Calendar $calendar)
    {
        // TODO: Implement findOneByDateAndCalendar() method.
    }

    public function update(Occurrence $occurrence)
    {
        // TODO: Implement update() method.
    }

    public function findAllByEventUnmodified(Event $event)
    {
        // TODO: Implement findAllByEventUnmodified() method.
    }

    public function remove(Occurrence $occurrence)
    {
        // TODO: Implement remove() method.
    }
}