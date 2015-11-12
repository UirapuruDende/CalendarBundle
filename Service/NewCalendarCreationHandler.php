<?php
namespace Dende\CalendarBundle\Service;
use Dende\Calendar\Application\Factory\CalendarFactory;
use Dende\CalendarBundle\Event\CalendarAfterCreationEvent;
use Dende\CalendarBundle\Events;
use Dende\CalendarBundle\Repository\ORM\CalendarRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Form;

/**
 * Class NewCalendarCreationHandler
 * @package Dende\CalendarBundle\Service
 * @todo move to component
 */
class NewCalendarCreationHandler
{
    /**
     * @var CalendarFactory
     */
    private $calendarFactory;

    /**
     * @var CalendarRepository
     */
    private $calendarRepository;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * NewCalendarCreationHandler constructor.
     * @param CalendarFactory $calendarFactory
     * @param CalendarRepository $calendarRepository
     */
    public function __construct(CalendarFactory $calendarFactory, CalendarRepository $calendarRepository, EventDispatcherInterface $eventDispatcher)
    {
        $this->calendarFactory = $calendarFactory;
        $this->calendarRepository = $calendarRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param Form $form
     * @param $command
     */
    public function handleForm(Form $form, $command){
        $newCalendarName = $form->get("new_calendar_name")->getData();

        if(!is_null($newCalendarName))
        {
            $newCalendar = $this->calendarFactory->createFromArray(["title" => $newCalendarName]);
            $this->calendarRepository->insert($newCalendar);
            $this->eventDispatcher->dispatch(
                Events::CALENDAR_AFTER_CREATION,
                new CalendarAfterCreationEvent($newCalendar)
            );
            $command->calendar = $newCalendar;
        }
    }
}