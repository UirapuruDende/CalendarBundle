<?php
namespace Dende\CalendarBundle\Tests\DataFixtures\ORM;

use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\CalendarId;
use Dende\CommonBundle\DataFixtures\BaseFixture;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class CalendarsData
 * @package Dende\CalendarBundle\Tests\DataFixtures\Standard\ORM
 */
final class CalendarsData extends BaseFixture implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /** @var string  */
    protected $dir = __DIR__;

    /**
     * @return int
     */
    public function getOrder()
    {
        return 0;
    }

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param $params
     * @return Calendar
     */
    public function insert($params)
    {
        return $this->container->get("dende_calendar.factory.calendar")->createFromArray([
            "id" => $params["id"],
            "title" => $params["name"]
        ]);
    }
}
