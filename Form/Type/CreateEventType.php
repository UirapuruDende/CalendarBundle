<?php
namespace Dende\CalendarBundle\Form\Type;

use Dende\Calendar\Domain\Calendar\Event;
use Dende\Calendar\Domain\Calendar\Event\EventType;
use Dende\Calendar\Domain\Calendar\Event\Repetitions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

final class CreateEventType extends AbstractType
{

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("calendar", "entity", [
                "class" => "Dende\Calendar\Domain\Calendar",
                "property" => "name",
            ])
            ->add("type", "choice", [
                "choices" => EventType::$availableTypes
            ])
            ->add("startDate", "datetime")
            ->add("endDate", "datetime")
            ->add("duration", "integer")
            ->add("title", "text")
            ->add("repetitionDays", "choice", [
                "choices" => Repetitions::$availableWeekdays,
                "multiple" => true,
                "expanded" => true
            ])
            ->add("submit", "submit")
        ;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'create_event';
    }

}