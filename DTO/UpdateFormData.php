<?php
namespace Dende\CalendarBundle\DTO;

use DateTime;
use Dende\Calendar\Application\Service\OccurrenceUpdateManager;
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
     * @var RangeDatesFormData
     */
    protected $eventDates;

    /**
     * @var RangeDatesFormData
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
     *
     * @param OccurrenceInterface $occurrence
     * @param string              $title
     * @param DateTime            $startDate
     * @param DateTime            $endDate
     * @param Repetitions         $repetitions
     * @param string              $method
     */
    public function __construct(OccurrenceInterface $occurrence, string $title, DateTime $eventStartDate, DateTime $eventEndDate, DateTime $occurrenceStartDate, DateTime $occurrenceEndDate, Repetitions $repetitions, string $method)
    {
        $this->occurrence = $occurrence;
        $this->title = $title;
        $this->eventDates = new RangeDatesFormData($eventStartDate, $eventEndDate);
        $this->occurrenceDates = new RangeDatesFormData($occurrenceStartDate, $occurrenceEndDate);
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
            $request->get('method', OccurrenceUpdateManager::MODE_SINGLE)
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

    public function eventDates() : RangeDatesFormData
    {
        return $this->eventDates;
    }

    public function setEventDates(RangeDatesFormData $eventDates)
    {
        $this->eventDates = $eventDates;
    }

    public function occurrenceDates() : RangeDatesFormData
    {
        return $this->occurrenceDates;
    }

    public function setOccurrenceDates(RangeDatesFormData $occurrenceDates)
    {
        $this->occurrenceDates = $occurrenceDates;
    }
}
