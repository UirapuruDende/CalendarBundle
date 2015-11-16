<?php
namespace Dende\CalendarBundle\Repository\ORM;

use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\Event;
use Dende\Calendar\Domain\Repository\EventRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

/**
 * Class OccurrenceRepository
 * @package Dende\CommonBundle\Repository\ORM
 */
class EventRepository extends EntityRepository implements EventRepositoryInterface
{
    /**
     * @param Event $event
     */
    public function insert($event)
    {
        $em = $this->getEntityManager();

        $em->persist($event);
        $em->flush();
    }

    public function update($event)
    {
        $this->getEntityManager()->merge($event);
        $this->getEntityManager()->flush();
    }

    /**
     * @param $event
     */
    public function remove($event)
    {
        $em = $this->getEntityManager();
        $em->remove($event);
        $em->flush();
    }
}
