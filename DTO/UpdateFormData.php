<?php
namespace Dende\CalendarBundle\DTO;

use DateTime;
use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\Event\OccurrenceInterface;
use Dende\Calendar\Domain\Calendar\Event\Repetitions;

class UpdateFormData
{
    /**
     * @var Calendar
     */
    public $calendar;

    /**
     * @var string
     */
    public $newCalendarName;

    /**
     * @var OccurrenceInterface
     */
    public $occurrence;

    /**
     * @var string
     */
    public $title;

    /**
     * @var DateTime
     */
    public $startDate;

    /**
     * @var DateTime
     */
    public $endDate;

    /**
     * @var Repetitions
     */
    public $repetitions;

    /**
     * @var string
     */
    public $method;
}
