<?php
namespace Dende\CalendarBundle\Form\Type;

use Dende\Calendar\Domain\Calendar\Event;
use Dende\Calendar\Domain\Calendar\Event\EventType;
use Dende\Calendar\Domain\Calendar\Event\Repetitions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

final class CreateEventType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("calendar", "entity", [
                "class" => "Calendar:Calendar",
                "choice_label" => "name",
                "em" => $options["model_manager_name"]
            ])
            ->add("type", "choice", [
                "choices" => array_combine(EventType::$availableTypes, EventType::$availableTypes),
            ])
            ->add("startDate", "datetime", ['widget' => 'single_text', 'with_seconds' => false, 'format' => 'Y-M-dd HH:mm' ])
            ->add("endDate", "datetime", ['widget' => 'single_text', 'with_seconds' => false, 'format' => 'Y-M-dd HH:mm'])
            ->add("duration", "integer")
            ->add("title", "text")
            ->add("repetitionDays", "choice", [
                "choices" => Repetitions::$availableWeekdays,
                "multiple" => true,
                "expanded" => true,
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

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Dende\Calendar\Application\Command\CreateEventCommand',
            'model_manager_name' => 'default'
        ]);

        $resolver->setAllowedTypes([
            'model_manager_name' => 'string',
        ]);
    }
}
