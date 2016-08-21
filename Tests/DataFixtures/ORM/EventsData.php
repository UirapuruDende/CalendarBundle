<?php
namespace Dende\CalendarBundle\Tests\DataFixtures\ORM;

use DateTime;
use Dende\Calendar\Application\Command\CreateEventCommand;
use Dende\Calendar\Application\Factory\EventFactory;
use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\Event;
use Dende\Calendar\Domain\Calendar\Event\Duration;
use Dende\Calendar\Domain\Calendar\Event\EventType;
use Dende\CommonBundle\DataFixtures\BaseFixture;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class EventsData
 * @package Dende\CalendarBundle\Tests\DataFixtures\Standard\ORM
 */
final class EventsData extends BaseFixture implements ContainerAwareInterface
{
    /** @var string $dir */
    protected $dir = __DIR__;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @return int
     */
    public function getOrder()
    {
        return 10;
    }

    /**
     * @param $params
     * @return Event
     */
    public function insert($params)
    {
        $array = [
            "calendar" => $this->getReference($params["calendar"]),
            "duration" => new Duration($params["duration"]),
            "startDate" => new DateTime($params["startDate"]),
            "endDate" => new DateTime($params["endDate"]),
            "repetitions" => new Event\Repetitions($params["repetitions"]),
            "title" => $params["title"],
            "type" => new EventType($params["type"]),
            "previousEvent" => isset($params["previousEvent"]) ? $this->getReference($params["previousEvent"]) : null,
        ];

        $event = $this->getContainer()->get('dende_calendar.factory.event')->createFromArray($array);

        return $event;
    }

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }
}
