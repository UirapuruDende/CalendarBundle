<?php
namespace Dende\CalendarBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;

/**
 * Class RestController
 * @package Dende\CalendarBundle\Controller
 */
final class RestController extends FOSRestController
{
    /**
     * @Rest\View()
     * @Rest\Route("calendar")
     * @Rest\Get
     */
    public function getCalendarsAction()
    {
        $calendars = $this->getDoctrine()->getRepository("Calendar:Calendar")->findAll();

        return $calendars;
    }

    /**
     * @Rest\View()
     * @Rest\Route(path="/api/event")
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
}
