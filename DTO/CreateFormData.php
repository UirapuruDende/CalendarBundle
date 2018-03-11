<?php
namespace Dende\CalendarBundle\DTO;

use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\CalendarId;
use Dende\Calendar\Domain\Calendar\Event\EventType;
use Dende\Calendar\Domain\Calendar\Event\Repetitions;
use Ramsey\Uuid\Uuid;

class CreateFormData
{
    /** @var Calendar */
    protected $calendar;

    /** @var EventType */
    protected $type;

    /** @var string */
    protected $newCalendarName;

    /** @var RangeDatesFormData */
    protected $eventDates;

    /** @var string */
    protected $title;

    /** @var Repetitions */
    protected $repetitions;

    public function __construct(Calendar $calendar = null, EventType $type, string $newCalendarName, RangeDatesFormData $eventDates, string $title, Repetitions $repetitions)
    {
        $this->calendar = $calendar ?: new Calendar(Uuid::uuid4(), '');
        $this->type = $type;
        $this->newCalendarName = $newCalendarName;
        $this->eventDates = $eventDates;
        $this->title = $title;
        $this->repetitions = $repetitions;
    }

    public function calendar()
    {
        return $this->calendar;
    }

    public function setCalendar(Calendar $calendar = null)
    {
        $this->calendar = $calendar;
    }

    public function type() : EventType
    {
        return $this->type;
    }

    public function setType(EventType $type)
    {
        $this->type = $type;
    }

    public function newCalendarName()
    {
        return $this->newCalendarName;
    }

    public function setNewCalendarName(string $newCalendarName = null)
    {
        $this->newCalendarName = $newCalendarName;
    }

    public function eventDates() : RangeDatesFormData
    {
        return $this->eventDates;
    }

    public function title() : string
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function repetitions() : Repetitions
    {
        return $this->repetitions;
    }

    public function setRepetitions(Repetitions $repetitions)
    {
        $this->repetitions = $repetitions;
    }

    public function setEventDates(RangeDatesFormData $eventDates)
    {
        $this->eventDates = $eventDates;
    }
}
