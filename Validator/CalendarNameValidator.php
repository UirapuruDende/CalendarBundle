<?php
namespace Dende\CalendarBundle\Validator;

use Dende\Calendar\Application\Command\CreateEventCommand;
use Dende\Calendar\Application\Command\UpdateEventCommand;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CalendarNameValidator extends ConstraintValidator
{
    /**
     * @param CreateEventCommand|UpdateEventCommand $command
     * @param CalendarNameConstraint $constraint
     */
    public function validate($command, Constraint $constraint)
    {
        $newCalendarName = $this->context->getRoot()->get('new_calendar_name')->getData();

        if(is_null($command->calendar) && is_null($newCalendarName))
        {
            $this->context->addViolationAt('calendar', $constraint->message);
            $this->context->addViolationAt('new_calendar_name', $constraint->message);
        }
    }
}
