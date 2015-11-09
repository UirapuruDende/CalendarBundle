<?php
namespace Dende\CalendarBundle\Repository\ORM;

use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\Event;
use Dende\Calendar\Domain\Repository\EventRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

/**
 * Class CalendarRepository
 * @package Dende\CalendarBundle\Repository\ORM
 */
class CalendarRepository extends EntityRepository
{
    /**
     * @param Calendar $calendar
     */
    public function insert($calendar)
    {
        $em = $this->getEntityManager();
        $em->persist($calendar);
        $em->flush();
    }
}
