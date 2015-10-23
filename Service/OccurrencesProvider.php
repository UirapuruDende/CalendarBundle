<?php
namespace Dende\CalendarBundle\Service;

use DateTime;
use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\Event\EventId;
use Dende\Calendar\Domain\Calendar\Event\Occurrence;
use Dende\Calendar\Domain\Repository\OccurrenceRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Class OccurrenceProvider
 * @package Dende\CalendarBundle\Service
 */
final class OccurrencesProvider
{
    private $colors = [
        'red',
        'blue',
        'green',
        'orange',
        'yellow',
        'light-blue'
    ];



    /**
     * @var OccurrenceRepositoryInterface
     */
    private $occurrenceRepository;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var string
     */
    private $eventEditRoute;

    /**
     * OccurrenceProvider constructor.
     * @param OccurrenceRepositoryInterface $occurrenceRepository
     * @param Router $router
     * @param $routeName
     */
    public function __construct(OccurrenceRepositoryInterface $occurrenceRepository, Router $router, $routeName)
    {
        $this->occurrenceRepository = $occurrenceRepository;
        $this->router = $router;
        $this->eventEditRoute = $routeName;
    }

    /**
     * @param Calendar $calendar
     * @param DateTime $start
     * @param DateTime $end
     */
    public function get(Calendar $calendar, DateTime $start, DateTime $end)
    {
        $collection = $this->occurrenceRepository->findByCalendar($calendar, $start, $end);

        $collection = array_map([$this, 'convert'], $collection);

        return $collection;
    }

    /**
     * @param Occurrence $occurrence
     * @return array
     */
    public function convert(Occurrence $occurrence) {

        $eventId = $occurrence->event()->id();

        $id = (int) ($eventId instanceof EventId ? $eventId->id() : $eventId);

        return [
            "title" => $occurrence->event()->title(),
            "start" => $occurrence->startDate()->format("Y-m-d H:i:s"),
            "end" => $occurrence->endDate()->format("Y-m-d H:i:s"),
            "backgroundColor" => $this->colors[$id%count($this->colors)],
            "url" => $this->router->generate($this->eventEditRoute, ['occurrence' => $occurrence->id()]),
            "textColor" => 'black',
            "editable" => true
        ];
    }
}