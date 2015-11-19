<?php
namespace Dende\CalendarBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CalendarNameConstraint extends Constraint
{
    public $message = 'dende_calendar.validation.choose_calendar_or_provide_new_name';

    public function validatedBy()
    {
        return 'validator.calendar_name_validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
