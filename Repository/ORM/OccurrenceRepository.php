<?php
namespace Dende\CalendarBundle\Repository\ORM;

use DateTime;
use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\Event;
use Dende\Calendar\Domain\Calendar\Event\Occurrence;
use Dende\Calendar\Domain\Repository\OccurrenceRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Traversable;

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
        $queryBuilder = $this->createQueryBuilder('o');
        $expr = $queryBuilder->expr();

        $queryBuilder
            ->innerJoin('o.event', 'e')
            ->where($expr->andX(
                $expr->gt("o.startDate", ':start'),
                $expr->lt("o.endDate", ':end')
            ))
            ->andWhere('o.deletedAt is NULL')
            ->orderBy('o.startDate', 'ASC')
            ->setParameters([
                'start' => $start,
                'end' => $end,
            ]);

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

    public function insert($occurrences)
    {
        $em = $this->getEntityManager();

        if($occurrences instanceof Occurrence) {
            $em->persist($occurrences);
            $em->flush($occurrences);

            return;
        } elseif(is_array($occurrences) || $occurrences instanceof Traversable) {
            /** @var Occurrence $occurrence */
            foreach($occurrences as $occurrence) {
                $em->persist($occurrence);
            }

            $em->flush();

            return;
        }

        throw new \Exception(sprintf(
            "Argument is unknown type! Should be %s class or collection/array of %s class!",
            Occurrence::class,
            Occurrence::class
        ));
    }

    public function insertCollection($occurrenceCollection)
    {
        $this->insert($occurrenceCollection);
    }

    public function findAllByEvent(Event $event)
    {
        // TODO: Implement findAllByEvent() method.
    }

    public function findOneByDateAndCalendar(DateTime $date, Calendar $calendar)
    {
        // TODO: Implement findOneByDateAndCalendar() method.
    }

    /**
     * @param Occurrence|Occurrence[] $occurrences
     * @throws \Exception
     */
    public function update($occurrences)
    {
        $em = $this->getEntityManager();

        if($occurrences instanceof Occurrence) {
            $em->merge($occurrences);
            $em->flush($occurrences);

            return;
        } elseif(is_array($occurrences) || $occurrences instanceof Traversable) {
            /** @var Occurrence $occurrence */
            foreach($occurrences as $occurrence) {
                $em->merge($occurrence);
            }

            $em->flush();

            return;
        }

        throw new \Exception(sprintf(
            "Argument is unknown type! Should be %s class or collection/array of %s class!",
            Occurrence::class,
            Occurrence::class
        ));
    }

    public function findAllByEventUnmodified(Event $event)
    {
        // TODO: Implement findAllByEventUnmodified() method.
    }

    /**
     * @param Occurrence|Occurrence[]|ArrayCollection $occurrences
     * @throws \Exception
     */
    public function remove($occurrences)
    {
        $em = $this->getEntityManager();

        if($occurrences instanceof Occurrence) {
            $occurrences->setDeletedAt(new \DateTime("now"));
            $em->flush($occurrences);

            return;
        } elseif(is_array($occurrences) || $occurrences instanceof Traversable) {
            $date = new \DateTime("now");

            /** @var Occurrence $occurrence */
            foreach($occurrences as $occurrence) {
                $occurrence->setDeletedAt($date);
            }

            $em->flush();

            return;
        }

        throw new \Exception(sprintf(
            "Argument is unknown type! Should be %s class or collection/array of %s class!",
            Occurrence::class,
            Occurrence::class
        ));

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
}
