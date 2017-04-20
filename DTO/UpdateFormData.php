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

    /**
     * UpdateFormData constructor.
     * @param OccurrenceInterface $occurrence
     * @param string $title
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param Repetitions $repetitions
     * @param string $method
     */
    public function __construct(OccurrenceInterface $occurrence, string $title, DateTime $startDate, DateTime $endDate, Repetitions $repetitions, string $method)
    {
        $this->occurrence = $occurrence;
        $this->title = $title;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->repetitions = $repetitions;
        $this->method = $method;
    }

    public static function fromRequest(Request $request)
    {
        return new self(
            $request->get('occurrence'),
            $request->get('title', ''),
            new DateTime($request->get('startDate')),
            new DateTime($request->get('endDate')),
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
     * @return DateTime
     */
    public function startDate(): DateTime
    {
        return $this->startDate;
    }

    /**
     * @return DateTime
     */
    public function endDate(): DateTime
    {
        return $this->endDate;
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
     * @param DateTime $startDate
     */
    public function setStartDate(DateTime $startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @param DateTime $endDate
     */
    public function setEndDate(DateTime $endDate)
    {
        $this->endDate = $endDate;
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
}
