<?php
namespace Dende\CalendarBundle\DTO;

use DateTime;
use Dende\Calendar\Application\Handler\UpdateEventHandler;
use Dende\Calendar\Domain\Calendar\Event\OccurrenceInterface;
use Dende\Calendar\Domain\Calendar\Event\Repetitions;
use Symfony\Component\HttpFoundation\Request;

class UpdateFormData
{
    /**
     * @var OccurrenceInterface
     */
    protected $occurrence;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var DateTime[]|array
     */
    protected $eventDates;

    /**
     * @var DateTime[]|array
     */
    protected $occurrenceDates;

    /**
     * @var Repetitions
     */
    protected $repetitions;

    /**
     * @var string
     */
    protected $method;

    /**
     * UpdateFormData constructor.
     * @param OccurrenceInterface $occurrence
     * @param string $title
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param Repetitions $repetitions
     * @param string $method
     */
    public function __construct(OccurrenceInterface $occurrence, string $title, DateTime $eventStartDate, DateTime $eventEndDate, DateTime $occurrenceStartDate, DateTime $occurrenceEndDate, Repetitions $repetitions, string $method)
    {
        $this->occurrence = $occurrence;
        $this->title = $title;
        $this->eventDates = ['startDate' => $eventStartDate, 'endDate' => $eventEndDate];
        $this->occurrenceDates = ['startDate' => $occurrenceStartDate, 'endDate' => $occurrenceEndDate];
        $this->repetitions = $repetitions;
        $this->method = $method;
    }

    public static function fromRequest(Request $request)
    {
        return new self(
            $request->get('occurrence'),
            $request->get('title', ''),
            new DateTime($request->get('eventDate.startDate')),
            new DateTime($request->get('eventDate.endDate')),
            new DateTime($request->get('occurrenceDate.startDate')),
            new DateTime($request->get('occurrenceDate.endDate')),
            new Repetitions($request->get('repetitions', [])),
            $request->get('method', UpdateEventHandler::MODE_SINGLE)
        );
    }

    /**
     * @return OccurrenceInterface
     */
    public function occurrence(): OccurrenceInterface
    {
        return $this->occurrence;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * @return Repetitions
     */
    public function repetitions(): Repetitions
    {
        return $this->repetitions;
    }

    /**
     * @return string
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * @param OccurrenceInterface $occurrence
     */
    public function setOccurrence(OccurrenceInterface $occurrence)
    {
        $this->occurrence = $occurrence;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @param Repetitions $repetitions
     */
    public function setRepetitions(Repetitions $repetitions)
    {
        $this->repetitions = $repetitions;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method)
    {
        $this->method = $method;
    }

    /**
     * @return array|DateTime[]
     */
    public function getEventDates()
    {
        return $this->eventDates;
    }

    /**
     * @param array|DateTime[] $eventDates
     */
    public function setEventDates($eventDates)
    {
        $this->eventDates = $eventDates;
    }

    /**
     * @return array|DateTime[]
     */
    public function getOccurrenceDates()
    {
        return $this->occurrenceDates;
    }

    /**
     * @param array|DateTime[] $occurrenceDates
     */
    public function setOccurrenceDates($occurrenceDates)
    {
        $this->occurrenceDates = $occurrenceDates;
    }
}
