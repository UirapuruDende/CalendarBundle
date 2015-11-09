<?php
namespace Dende\CalendarBundle\Service;
use Dende\Calendar\Application\Factory\CalendarFactory;
use Dende\CalendarBundle\Repository\ORM\CalendarRepository;
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
     * NewCalendarCreationHandler constructor.
     * @param CalendarFactory $calendarFactory
     * @param CalendarRepository $calendarRepository
     */
    public function __construct(CalendarFactory $calendarFactory, CalendarRepository $calendarRepository)
    {
        $this->calendarFactory = $calendarFactory;
        $this->calendarRepository = $calendarRepository;
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
            $command->calendar = $newCalendar;
        }
    }
}