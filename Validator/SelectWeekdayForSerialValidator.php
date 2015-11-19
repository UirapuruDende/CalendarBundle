<?php
namespace Dende\CalendarBundle\Validator;

use Dende\Calendar\Application\Command\CreateEventCommand;
use Dende\Calendar\Application\Command\UpdateEventCommand;
use Dende\Calendar\Domain\Calendar\Event\EventType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SelectWeekdayForSerialValidator extends ConstraintValidator
{
    /**
     * @param CreateEventCommand|UpdateEventCommand $command
     * @param SelectWeekdayForSerialConstraint $constraint
     */
    public function validate($command, Constraint $constraint)
    {
        if($command->type === EventType::TYPE_WEEKLY && empty($command->repetitionDays))
        {
            $this->context->addViolationAt('type', $constraint->message);
        }
    }
}
