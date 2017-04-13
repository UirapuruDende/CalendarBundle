<?php
namespace Dende\CalendarBundle\DTO;

use DateTime;
use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\Event\EventType;
use Dende\Calendar\Domain\Calendar\Event\Repetitions;

class CreateFormData
{
    protected $calendar;

    protected $type;

    protected $newCalendarName;

    protected $startDate;

    protected $endDate;

    protected $title;

    protected $repetitions;

    public function __construct(Calendar $calendar = null, EventType $type = null, string $newCalendarName = '', DateTime $startDate, DateTime $endDate, string $title = '', Repetitions $repetitions)
    {
        $this->calendar = $calendar;
        $this->type = $type;
        $this->newCalendarName = $newCalendarName;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->title = $title;
        $this->repetitions = $repetitions;
    }

    public function calendar() : Calendar
    {
        return $this->calendar;
    }

    public function type() : EventType
    {
        return $this->type;
    }

    public function newCalendarName() : string
    {
        return $this->newCalendarName;
    }

    public function startDate() : DateTime
    {
        return $this->startDate;
    }

    public function getEndDate() : DateTime
    {
        return $this->endDate;
    }

    public function title() : string
    {
        return $this->title;
    }

    public function repetitions() : Repetitions
    {
        return $this->repetitions;
    }
}