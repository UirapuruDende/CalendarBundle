<?php
namespace Dende\CalendarBundle\Controller;

use Dende\Calendar\Domain\Calendar\CalendarId;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\FOSRestController;

/**
 * Class RestController
 * @package Dende\CalendarBundle\Controller
 */
final class RestController extends FOSRestController
{
    /**
     * @Route("/")
     * @return array
     */
    public function linksAction()
    {
        return [
            "calendars" => ["href" => $this->generateUrl("get_calendars", [], true)],
            "events" => ["href" => $this->generateUrl("get_events", [], true)],
            "occurrences" => ["href" => $this->generateUrl("get_occurrences", [], true)]
        ];
    }

    /**
     * @Rest\View()
     * @Rest\Get
     */
    public function getCalendarsAction()
    {
        $calendars = $this->getDoctrine()->getRepository("Calendar:Calendar")->findAll();

        return $calendars;
    }

    /**
     * @Rest\View()
     * @Rest\Get
     */
    public function getCalendarAction($id)
    {
        $calendar = $this->getDoctrine()->getRepository("Calendar:Calendar")->findOneById($id);

        return $calendar;
    }


    /**
     * @Rest\View()
     * @Rest\Get
     */
    public function getCalendarEventsAction($id)
    {
        $calendar = $this->getDoctrine()->getRepository("Calendar:Calendar")->findOneById($id);

        $events = $this->getDoctrine()->getManager("view_model")->getRepository("ViewModel:Calendar\Event")->findByCalendar($calendar);

        return $events;
    }

    /**
     * @Rest\View()
     * @Rest\Get
     */
    public function getEventsAction()
    {
        $events = $this->getDoctrine()->getManager("view_model")->getRepository("ViewModel:Calendar\Event")->findAll();

        return $events;
    }


    /**
     * @Rest\View()
     * @Rest\Get
     */
    public function getEventAction($id)
    {
        $events = $this->getDoctrine()->getManager("view_model")->getRepository("ViewModel:Calendar\Event")->findOneById($id);

        return $events;
    }

    /**
     * @Rest\View()
     * @Rest\Get
     */
    public function getOccurrencesAction()
    {
        $events = $this->getDoctrine()->getManager("view_model")->getRepository("ViewModel:Calendar\Event\Occurrence")->findAll();

        return $events;
    }


    /**
     * @Rest\View()
     * @Rest\Get
     */
    public function getOccurrenceAction($id)
    {
        $occurrence = $this->getDoctrine()->getManager("view_model")->getRepository("ViewModel:Calendar\Event\Occurrence")->findOneById($id);
        return $occurrence;
    }

    /**
     * @Rest\View()
     * @Rest\Get
     */
    public function getEventOccurrencesAction($id)
    {
        $occurrences = $this->getDoctrine()->getManager("view_model")->getRepository("ViewModel:Calendar\Event\Occurrence")->findByEvent($id);
        return $occurrences;
    }
}
