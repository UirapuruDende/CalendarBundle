<?php
namespace Dende\CalendarBundle\Form\Type;

use Dende\Calendar\Application\Command\UpdateEventCommand;
use Dende\Calendar\Application\Handler\UpdateEventHandler;
use Dende\Calendar\Domain\Calendar\Event\EventType;
use Dende\Calendar\Domain\Calendar\Event\Repetitions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class UpdateEventType
 * @package Dende\CalendarBundle\Form\Type
 * @todo setup validation so:
 * @todo - calendar must be selected or name for new provided
 * @todo - if event is weekly at least one repetition chosen
 */
class UpdateEventType extends AbstractType
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
                'format' => 'Y-MM-dd HH:mm',
                'attr' => [
                    'class' => 'form_datetime'
                ],
                "label" => "dende_calendar.form.start_date.label"
            ])
            ->add("endDate", "datetime", [
                'widget' => 'single_text',
                'with_seconds' => false,
                'format' => 'Y-MM-dd HH:mm',
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
            ->add("method", "hidden", [
                "data" => UpdateEventHandler::MODE_NEXT_INCLUSIVE
            ])
            ->add("repetitionDays", "choice", [
                "choices" => array_map($this->updateNames('repetition_days'), Repetitions::$availableWeekdays),
                "multiple" => true,
                "expanded" => true,
                "label" => "dende_calendar.form.repetition_days.label"
            ])
            ->add("delete_event", "submit", [
                "label" => "dende_calendar.form.delete_event.label",
                "attr" => [
                    "class" => "pull-right"
                ]
            ])
            ->add("submit", "submit", [
                "label" => "dende_calendar.form.submit_update.label"
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var UpdateEventCommand $command */
            $command = $event->getData();
            $form = $event->getForm();

            $occurrence = $command->occurrence;

            if (!$occurrence) {
                throw new \Exception("Occurrence is null!");
            }

            $event = $occurrence->event();

            if (!$event) {
                throw new \Exception("Event is null!");
            }

            if ($event->isType(EventType::TYPE_WEEKLY)) {
                $form->add("delete_occurrence", "submit", [
                    "label" => "dende_calendar.form.delete_occurrence.label",
                    "attr" => [
                        "class" => "pull-right"
                    ]
                ]);
            }

        });
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UpdateEventCommand::class,
            'model_manager_name' => 'default'
        ]);

        $resolver->setAllowedTypes([
            'model_manager_name' => 'string',
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'update_event';
    }
}
