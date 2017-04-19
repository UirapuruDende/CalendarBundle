<?php
namespace Dende\CalendarBundle\DTO;

use DateTime;
use Dende\Calendar\Domain\Calendar\Event\OccurrenceInterface;
use Dende\Calendar\Domain\Calendar\Event\Repetitions;

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

    /**
     * @return OccurrenceInterface
     */
    public function cccurrence(): OccurrenceInterface
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
}
