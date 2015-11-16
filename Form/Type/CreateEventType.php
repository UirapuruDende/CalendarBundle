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
    use UpdateNameTrait;

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("calendar", "entity", [
                "required" => false,
                "class" => "Calendar:Calendar",
                "choice_label" => "name",
                "em" => $options["model_manager_name"],
                "label" => "dende_calendar.form.calendar.label"
            ])
            ->add("new_calendar_name", "text", [
                "mapped" => false,
                "required" => false,
                "label" => "dende_calendar.form.new_calendar_name.label"
            ])
            ->add("type", "choice", [
                "choices" => array_combine(
                    EventType::$availableTypes,
                    array_map($this->updateNames('type'), EventType::$availableTypes)
                ),
                "label" => "dende_calendar.form.type.label"
            ])
            ->add("startDate", "datetime", [
                'widget' => 'single_text',
                'with_seconds' => false,
                'format' => 'Y-M-dd HH:mm',
                'attr' => [
                    'class' => 'form_datetime'
                ],
                "label" => "dende_calendar.form.start_date.label"
            ])
            ->add("endDate", "datetime", [
                'widget' => 'single_text',
                'with_seconds' => false,
                'format' => 'Y-M-dd HH:mm',
                'attr' => [
                    'class' => 'form_datetime'
                ],
                "label" => "dende_calendar.form.end_date.label"
            ])
            ->add("duration", "integer", [
                "label" => "dende_calendar.form.duration.label"
            ])
            ->add("title", "text", [
                "label" => "dende_calendar.form.title.label"
            ])
            ->add("repetitionDays", "choice", [
                "choices" => array_map($this->updateNames('repetition_days'), Repetitions::$availableWeekdays),
                "multiple" => true,
                "expanded" => true,
                "label" => "dende_calendar.form.repetition_days.label"
            ])
            ->add("submit", "submit", [
                "label" => "dende_calendar.form.submit.label"
            ])
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
