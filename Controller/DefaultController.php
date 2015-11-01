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

        $calendars = array_map(function (Calendar $calendar) {
            return $calendar->id();
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
    public function createEventAction(Request $request)
    {
        $command = new CreateEventCommand();

        if ($request->isMethod("GET") && !is_null($request->get('startDate')) && !is_null($request->get('endDate'))) {
            $command->startDate = Carbon::createFromFormat("YmdHi", $request->get('startDate'));
            $command->endDate = Carbon::createFromFormat("YmdHi", $request->get('endDate'));

            $command->duration = $command->startDate->diffInMinutes($command->endDate);
            $command->repetitionDays = [
                $command->startDate->dayOfWeek
            ];
        }

        $form = $this->createForm('create_event', $command);

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $command = $form->getData();
                $this->get("dende_calendar.handler.create_event")->handle($command);
                return $this->redirectToRoute("dende_calendar_default_index");
            }
        }

        return [
            "form" => $form->createView()
        ];
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
