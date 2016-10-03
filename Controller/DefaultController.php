<?php
namespace Dende\CalendarBundle\Controller;

use Carbon\Carbon;
use Dende\Calendar\Application\Command\CreateEventCommand;
use Dende\Calendar\Application\Command\UpdateEventCommand;
use Dende\Calendar\Application\Handler\UpdateEventHandler;
use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\Event;
use Dende\Calendar\Domain\Calendar\Event\EventType;
use Dende\Calendar\Domain\Calendar\Event\Occurrence;
use Dende\CalendarBundle\Form\Type\CreateEventType;
use Dende\CalendarBundle\Form\Type\UpdateEventType;
use Doctrine\ORM\EntityNotFoundException;
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
class DefaultController extends Controller
{
    /**
     * @Template("DendeCalendarBundle:Default:index.html.twig")
     * @Route("/")
     * @return array
     */
    public function indexAction()
    {
        $calendars = $this->get($this->getParameter("dende_calendar.calendar_repository_service_name"))->findAll();

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
        $start = Carbon::parse($request->get('start', 'this week'));
        $end = Carbon::parse($request->get('end', 'next week'));

        $events = $this->get('dende_calendar.occurrences_provider')->getAll($start, $end, !$request->get("noroute", false));

        return new JsonResponse($events);
    }

    /**
     * @Route("/occurrence/new", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template("DendeCalendarBundle:Default:createEvent.html.twig")
     * @return string
     */
    public function createEventAction(Request $request)
    {
        $response = new Response();
        $command = new CreateEventCommand();

        if ($request->isMethod("GET") && !is_null($request->get('startDate')) && !is_null($request->get('endDate'))) {
            $command->startDate = Carbon::createFromFormat("YmdHi", $request->get('startDate'));
            $command->endDate = Carbon::createFromFormat("YmdHi", $request->get('endDate'));
            $command->duration = $command->startDate->diffInMinutes($command->endDate);
            $command->endDate->addYear();
            $command->repetitionDays = [
                intval($command->startDate->format("N"))
            ];
        }

        $form = $this->createForm(CreateEventType::class, $command, [
            "model_manager_name" => $this->getParameter("dende_calendar.model_manager_name")
        ]);

        if ($request->isMethod("POST")) {
            
            $form->handleRequest($request);

            if ($form->isValid()) {
                /** @var UpdateEventCommand|CreateEventCommand $command */
                $command = $form->getData();

                if($command->newCalendarName) {
                    $this->get("dende_calendar.new_calendar_creation")->handle($command);
                }

                $this->get("dende_calendar.handler.create_event")->handle($command);
                $this->get("session")->getFlashBag()->add("success", "dende_calendar.flash.event_created_successfully");
                return $this->redirectToRoute("dende_calendar_default_index");
            } else {
                $this->get("session")->getFlashBag()->add("error", "dende_calendar.flash.event_creation_error");
                $response->setStatusCode(400);
            }
        }

        return $this->render("DendeCalendarBundle:Default:createEvent.html.twig", [
            "form" => $form->createView()
        ], $response);
    }

    /**
     * @Route("/occurrence/{occurrenceId}", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template("DendeCalendarBundle:Default:updateEvent.html.twig")
     * @return string
     */
    public function updateEventAction(Request $request, $occurrenceId)
    {
        /** @var Occurrence $occurrence */
        $occurrence = $this->get(
            $this->getParameter("dende_calendar.occurrence_repository_service_name")
        )->find($occurrenceId);

        if(!$occurrence) {
            throw new EntityNotFoundException('Occurrence entity not found in database');
        }

        $response = new Response();
        $command = new UpdateEventCommand();
        $command->occurrence = $occurrence;

        if ($request->isMethod("GET")) {

            /** @var Event $event */
            $event = $occurrence->event();
            $command->calendar = $event->calendar();
            $command->startDate = $occurrence->startDate();
            $command->endDate = $event->endDate();
            $command->duration = $event->duration()->minutes();
            $command->title = $event->title();
            $command->repetitionDays = $event->repetitions()->weekdays();
            $command->type = $event->type()->type();
        }

        $form = $this->createForm(UpdateEventType::class, $command, [
            "model_manager_name" => $this->getParameter("dende_calendar.model_manager_name")
        ]);

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                if ($form->get("delete_event")->isClicked()) {
                    $this->get('dende_calendar.handler.remove_event')->remove($occurrence->event());
                    return $this->redirectToRoute("dende_calendar_default_index");
                } elseif ($occurrence->event()->isType(EventType::TYPE_WEEKLY) && $form->get("delete_occurrence")->isClicked()) {
                    $this->get('dende_calendar.handler.remove_occurrence')->remove($occurrence);
                    return $this->redirectToRoute("dende_calendar_default_index");
                } else {
                    /** @var UpdateEventCommand|CreateEventCommand $command */
                    $command = $form->getData();

                    if($command->newCalendarName) {
                        $this->get("dende_calendar.new_calendar_creation")->handle($command);
                    }

                    $this->get("dende_calendar.handler.update_event")->handle($command);
                    $this->get("session")->getFlashBag()->add("success", "dende_calendar.flash.event_updated_successfully");
                    return $this->redirectToRoute("dende_calendar_default_index");
                }
            } else {
                $this->get("session")->getFlashBag()->add("error", "dende_calendar.flash.event_update_error");
                $response->setStatusCode(400);
            }
        }

        return $this->render("DendeCalendarBundle:Default:updateEvent.html.twig", [
            "form" => $form->createView()
        ], $response);
    }
}
