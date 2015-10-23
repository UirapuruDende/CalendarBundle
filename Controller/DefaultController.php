<?php
namespace Dende\CalendarBundle\Controller;

use Carbon\Carbon;
use Dende\Calendar\Application\Command\CreateEventCommand;
use Dende\Calendar\Application\Command\UpdateEventCommand;
use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\CalendarId;
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
        $calendars = $this->getDoctrine()->getRepository("Calendar:Calendar")->findAll();

        $calendars = array_map(function(Calendar $calendar){
            return $calendar->id()->id();
        }, $calendars);

        return [
            'calendars' => $calendars
        ];
    }

    /**
     * @Route("/events", options={"expose"=true})
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getEventsAction(Request $request)
    {
        $start = Carbon::parse($request->get('start'));
        $end = Carbon::parse($request->get('end'));

        $events = $this->get('dende_calendar.occurrences_provider')->getAll($start, $end);

        return new JsonResponse($events);
    }

    /**
     * @Route("/occurrence/new", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template()
     * @return string
     */
    public function createEventAction()
    {
        $form = $this->createForm('dende_calendar.form_type.create_event', new CreateEventCommand());
    }

    /**
     * @Route("/occurrence/{occurrence}", options={"expose"=true})
     * @ParamConverter("occurrence", class="Calendar:Calendar\Event\Occurrence")
     * @Method({"GET", "POST"})
     * @Template()
     * @return string
     */
    public function updateEventAction(Occurrence $occurrence)
    {
        $form = $this->createForm('dende_calendar.form_type.update_event', new UpdateEventCommand());
    }
}
