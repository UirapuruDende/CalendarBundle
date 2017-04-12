<?php
namespace Dende\CalendarBundle\DTO;

use DateTime;
use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\Event\OccurrenceInterface;
use Dende\Calendar\Domain\Calendar\Event\Repetitions;

class UpdateData
{
    /**
     * @var Calendar
     */
    protected $calendar;

    /**
     * @var string
     */
    protected $newCalendarName;

    /**
     * @var OccurrenceInterface
     */
    protected $occurrence;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var DateTime
     */
    protected $startDate;

    /**
     * @var DateTime
     */
    protected $endDate;

    /**
     * @var Repetitions
     */
    protected $repetitions;

    /**
     * @var string
     */
    protected $method;

    public function occurrence() : OccurrenceInterface
    {
        return $this->occurrence;
    }
}
