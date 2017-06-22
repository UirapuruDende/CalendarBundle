<?php
namespace Dende\CalendarBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\FOSRestController;

/**
 * Class RestController.
 */
final class RestController extends FOSRestController
{
    /**
     * @Route("/")
     *
     * @return array
     */
    public function linksAction()
    {
        return [
            'calendars'   => ['href' => $this->generateUrl('get_calendars', [], true)],
            'events'      => ['href' => $this->generateUrl('get_events', [], true)],
            'occurrences' => ['href' => $this->generateUrl('get_occurrences', [], true)],
        ];
    }

    /**
     * @Rest\View()
     * @Rest\Get
     */
    public function getCalendarsAction()
    {
        $calendars = $this->getRepo('Calendar:Calendar')->findAll();

        return $calendars;
    }

    /**
     * @Rest\View()
     * @Rest\Get
     */
    public function getCalendarAction($id)
    {
        $calendar = $this->getRepo('Calendar:Calendar')->findOneById($id);

        return $calendar;
    }

    /**
     * @Rest\View()
     * @Rest\Get
     */
    public function getCalendarEventsAction($id)
    {
        $calendar = $this->getRepo('Calendar:Calendar')->findOneById($id);

        $events = $this->getRepo("Calendar:Calendar\Event")->findByCalendar($calendar);

        return $events;
    }

    /**
     * @Rest\View()
     * @Rest\Get
     */
    public function getEventsAction()
    {
        $events = $this->getRepo("Calendar:Calendar\Event")->findAll();

        return $events;
    }

    /**
     * @Rest\View()
     * @Rest\Get
     */
    public function getEventAction($id)
    {
        $events = $this->getRepo("Calendar:Calendar\Event")->findOneById($id);

        return $events;
    }

    /**
     * @Rest\View()
     * @Rest\Get
     */
    public function getOccurrencesAction()
    {
        $events = $this->getRepo("Calendar:Calendar\Event\Occurrence")->findAll();

        return $events;
    }

    /**
     * @Rest\View()
     * @Rest\Get
     */
    public function getOccurrenceAction($id)
    {
        $occurrence = $this->getRepo("Calendar:Calendar\Event\Occurrence")->findOneById($id);

        return $occurrence;
    }

    /**
     * @Rest\View()
     * @Rest\Get
     */
    public function getEventOccurrencesAction($id)
    {
        $occurrences = $this->getRepo("Calendar:Calendar\Event\Occurrence")->findByEvent($id);

        return $occurrences;
    }

    /**
     * @param $class
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    private function getRepo($class)
    {
        return $this->get('dende_calendar.entity_manager')->getRepository($class);
    }
}
