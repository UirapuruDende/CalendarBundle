<?php
namespace Dende\CalendarBundle\DTO;

use DateTime;
use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\CalendarId;
use Dende\Calendar\Domain\Calendar\Event\EventType;
use Dende\Calendar\Domain\Calendar\Event\Repetitions;

class CreateFormData
{
    /** @var Calendar */
    protected $calendar;

    /** @var EventType */
    protected $type;

    /** @var string */
    protected $newCalendarName;

    /** @var DateTime */
    protected $startDate;

    /** @var DateTime */
    protected $endDate;

    /** @var string */
    protected $title;

    /** @var Repetitions */
    protected $repetitions;

    public function __construct(Calendar $calendar = null, EventType $type, string $newCalendarName = '', DateTime $startDate, DateTime $endDate, string $title, Repetitions $repetitions)
    {
        $this->calendar = $calendar ?: new Calendar(CalendarId::create(), '');
        $this->type = $type;
        $this->newCalendarName = $newCalendarName;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
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

    public function startDate() : DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(DateTime $startDate)
    {
        $this->startDate = $startDate;
    }

    public function endDate() : DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(DateTime $endDate)
    {
        $this->endDate = $endDate;
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
}