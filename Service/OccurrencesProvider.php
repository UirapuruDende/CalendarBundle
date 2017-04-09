<?php
namespace Dende\CalendarBundle\Service;

use DateTime;
use Dende\Calendar\Application\Repository\OccurrenceRepositoryInterface;
use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\Event\Occurrence;
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
     * @var bool
     */
    private $generateRoutes = true;

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
     * @param DateTime $start
     * @param DateTime $end
     */
    public function getAll(DateTime $start, DateTime $end, $generateRoutes = true)
    {
        $this->generateRoutes = $generateRoutes;

        $collection = $this->occurrenceRepository->findByPeriod($start, $end);

        $collection = array_map([$this, 'convert'], $collection);

        return $collection;
    }

    /**
     * @param Occurrence $occurrence
     * @return array
     */
    public function convert(Occurrence $occurrence)
    {
        $id = $occurrence->event()->id();

        $options = [
            "title" => $occurrence->event()->title(),
            "start" => $occurrence->startDate()->format("Y-m-d H:i:s"),
            "end" => $occurrence->endDate()->format("Y-m-d H:i:s"),
            "backgroundColor" => $this->colors[$id % count($this->colors)],
            "textColor" => 'black',
            "editable" => true
        ];

        if ($this->generateRoutes) {
            $options["url"] = $this->router->generate($this->eventEditRoute, ['occurrenceId' => $occurrence->id()]);
        }

        return $options;
    }
}
