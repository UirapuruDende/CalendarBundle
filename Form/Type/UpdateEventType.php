<?php
namespace Dende\CalendarBundle\Form\Type;

use Dende\Calendar\Application\Handler\UpdateEventHandler;
use Dende\Calendar\Domain\Calendar\Event\EventType;
use Dende\Calendar\Domain\Calendar\Event\Repetitions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

final class UpdateEventType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("calendar", "entity", [
                "class" => "Dende\Calendar\Domain\Calendar",
                "choice_label" => "name",
            ])
            ->add("type", "choice", [
                "choices" => array_combine(EventType::$availableTypes, EventType::$availableTypes),
            ])
            ->add("startDate", "datetime", ['widget' => 'single_text', 'with_seconds' => false, 'format' => 'Y-M-dd HH:mm' ])
            ->add("endDate", "datetime", ['widget' => 'single_text', 'with_seconds' => false, 'format' => 'Y-M-dd HH:mm' ])
            ->add("duration", "integer")
            ->add("title", "text")
            ->add("method", "hidden", [
                "data" => UpdateEventHandler::MODE_OVERWRITE
            ])
            ->add("repetitionDays", "choice", [
                "choices" => Repetitions::$availableWeekdays,
                "multiple" => true,
                "expanded" => true
            ])
            ->add("delete_event", "submit", [
                "label" => "Delete whole event",
                "attr" => [
                    "class" => "pull-right"
                ]
            ])
            ->add("delete_occurrence", "submit", [
                "label" => "Delete only this occurrence",
                "attr" => [
                    "class" => "pull-right"
                ]
            ])
            ->add("submit", "submit", [
                "label" => "Update event"
            ])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dende\Calendar\Application\Command\UpdateEventCommand'
        ));
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'update_event';
    }
}
