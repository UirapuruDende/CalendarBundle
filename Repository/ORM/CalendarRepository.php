<?php
namespace Dende\CalendarBundle\Repository\ORM;

use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\Event;
use Dende\Calendar\Domain\Repository\CalendarRepositoryInterface;
use Dende\Calendar\Domain\Repository\EventRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

/**
 * Class CalendarRepository
 * @package Dende\CalendarBundle\Repository\ORM
 */
class CalendarRepository extends EntityRepository implements CalendarRepositoryInterface
{
    public function update(Calendar $calendar)
    {
        // TODO: Implement update() method.
    }

    /**
     * @param Calendar $calendar
     */
    public function insert(Calendar $calendar)
    {
        $em = $this->getEntityManager();
        $em->persist($calendar);
        $em->flush();
    }

    public function remove(Calendar $calendar)
    {
        $em = $this->getEntityManager();
        $em->remove($calendar);
        $em->flush();
    }
}
