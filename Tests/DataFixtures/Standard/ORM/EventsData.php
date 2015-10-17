<?php
namespace Dende\CalendarBundle\Tests\DataFixtures\Standard\ORM;

use DateTime;
use Dende\Calendar\Application\Command\CreateEventCommand;
use Dende\Calendar\Application\Factory\EventFactory;
use Dende\Calendar\Domain\Calendar;
use Dende\CommonBundle\DataFixtures\BaseFixture;

/**
 * Class EventsData
 * @package Dende\CalendarBundle\Tests\DataFixtures\Standard\ORM
 */
final class EventsData extends BaseFixture
{
    /** @var string $dir */
    protected $dir = __DIR__;

    /**
     * @return int
     */
    public function getOrder()
    {
        return 10;
    }

    /**
     * @param $params
     * @return Calendar\Event
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

        $event = EventFactory::createFromCommand($command);

        return $event;
    }
}
