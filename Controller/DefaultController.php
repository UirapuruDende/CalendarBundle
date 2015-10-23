<?php
namespace Dende\CalendarBundle\Controller;

use Carbon\Carbon;
use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\Event\Occurrence;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 * @package Dende\CalendarBundle\Controller
 */
final class DefaultController extends Controller
{
    /**
     * @Template()
     * @Route("/")
     * @return Response
     */
    public function indexAction()
    {
        return [
            'calendarId' => "79ead9b9-775c-11e5-8d7d-1c6f658c64a2"
        ];
    }

    /**
     * @Route("/{calendar}/events", options={"expose"=true})
     * @ParamConverter("calendar", class="Calendar:Calendar")
     * @Method({"GET"})
     * @return string
     */
    public function getEventsAction(Calendar $calendar, Request $request)
    {
        $start = Carbon::parse($request->get('start'));
        $end = Carbon::parse($request->get('end'));

        $events = $this->get('dende_calendar.occurrences_provider')->get($calendar, $start, $end);

        return new JsonResponse($events);
    }


    /**
     * @Route("/occurrence/{occurrence}", options={"expose"=true})
     * @ParamConverter("occurrence", class="Calendar:Calendar\Event\Occurrence")
     * @Method({"GET"})
     * @Template()
     * @return string
     */
    public function updateEventAction(Occurrence $occurrence)
    {

    }
}
