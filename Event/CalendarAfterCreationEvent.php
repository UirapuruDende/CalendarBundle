<?php
namespace Dende\CalendarBundle\Event;

use Dende\Calendar\Domain\Calendar;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class CalendarAfterCreationEvent
 * @package Dende\CalendarBundle\Event
 */
class CalendarAfterCreationEvent extends Event
{
    /**
     * @var Calendar
     */
    public $calendar;

    /**
     * CalendarAfterCreationEvent constructor.
     * @param $calendar
     */
    public function __construct(Calendar $calendar)
    {
        $this->calendar = $calendar;
    }
}