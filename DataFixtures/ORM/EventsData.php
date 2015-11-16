<?php
namespace Dende\CalendarBundle\DataFixtures\ORM;

use DateTime;
use Dende\Calendar\Application\Command\CreateEventCommand;
use Dende\Calendar\Application\Factory\EventFactory;
use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\Event;
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
        $command = new CreateEventCommand();
        $command->calendar = $this->getReference($params["calendar"]);
        $command->duration = $params["duration"];
        $command->startDate = new DateTime($params["startDate"]);
        $command->endDate = new DateTime($params["endDate"]);
        $command->repetitionDays = $params["repetitions"];
        $command->title = $params["title"];
        $command->type = $params["type"];

        $event = $this->getContainer()->get('dende_calendar.factory.event')->createFromCommand($command);

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
