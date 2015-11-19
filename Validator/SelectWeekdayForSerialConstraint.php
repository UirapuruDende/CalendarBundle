<?php
namespace Dende\CalendarBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class SelectWeekdayForSerialConstraint extends Constraint
{
    public $message = 'dende_calendar.validation.choose_at_least_one_day_for_weekly';

    public function validatedBy()
    {
        return 'validator.weekday_for_serial_validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
