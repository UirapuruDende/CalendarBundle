<?php
namespace Dende\CalendarBundle\Form\Type;

use Dende\Calendar\Application\Command\UpdateEventCommand;
use Dende\Calendar\Application\Handler\UpdateEventHandler;
use Dende\Calendar\Domain\Calendar\Event;
use Dende\Calendar\Domain\Calendar\Event\EventType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateEventType
 * @package Dende\CalendarBundle\Form\Type
 */
class UpdateEventType extends AbstractEventType
{
    use UpdateNameTrait;

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add("delete_event", SubmitType::class, [
                "label" => "dende_calendar.form.delete_event.label",
                "attr" => [
                    "class" => "pull-right"
                ]
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var UpdateEventCommand $command */
            $command = $event->getData();
            $form = $event->getForm();

            $occurrence = $command->occurrence;

            if (!$occurrence) {
                throw new \Exception("Occurrence is null!");
            }

            /** @var Event $event */
            $event = $occurrence->event();

            if (!$event) {
                throw new \Exception("Event is null!");
            }

            if ($event->isSingle()) {
                $form->add("method", HiddenType::class, [
                    'data' => 'single'
                ]);
                $form->remove("repetitionDays");

            } else if ($event->isWeekly()) {
                $form->add("delete_occurrence", "submit", [
                    "label" => "dende_calendar.form.delete_occurrence.label",
                    "attr" => [
                        "class" => "pull-right"
                    ]
                ]);
                $form->add("method", ChoiceType::class, [
                    "label" => "dende_calendar.form.method.label",
                    'choices' => array_combine(UpdateEventHandler::$availableModes, array_map(function($mode) {
                        return sprintf('dende_calendar.form.method.choice.%s', $mode);
                    }, UpdateEventHandler::$availableModes)),
                    "data" => UpdateEventHandler::MODE_NEXT_INCLUSIVE
                ]);
            }
        });
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => UpdateEventCommand::class,
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
