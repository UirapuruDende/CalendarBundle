<?php
namespace Dende\CalendarBundle\Form\Type;

use Dende\Calendar\Application\Command\UpdateCommand;
use Dende\Calendar\Application\Handler\OccurrenceUpdateManager;
use Dende\Calendar\Domain\Calendar\Event;
use Dende\Calendar\Domain\Calendar\Event\EventType;
use Dende\CalendarBundle\DTO\UpdateFormData;
use Dende\CalendarBundle\Form\Type\UpdateEventType\RangeDatesType;
use Exception;
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
     * @throws \Symfony\Component\Form\Exception\AlreadySubmittedException
     * @throws \Symfony\Component\Form\Exception\LogicException
     * @throws \Symfony\Component\Form\Exception\UnexpectedTypeException
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->remove('type');

        $builder
            ->add("delete_event", SubmitType::class, [
                "label" => "dende_calendar.form.delete_event.label"
            ])
        ;

        $builder->add("occurrenceDates", RangeDatesType::class, [
            "label" => false
        ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var UpdateFormData $dto */
            $dto = $event->getData();
            $form = $event->getForm();

            $occurrence = $dto->occurrence();

            if (!$occurrence) {
                throw new Exception("Occurrence is null!");
            }

            /** @var Event $event */
            $event = $occurrence->event();

            if (!$event) {
                throw new Exception("Event is null!");
            }

            if ($event->isWeekly()) {
                $form->add(
                    'delete_occurrence', 'submit', [
                    "label" => 'dende_calendar.form.delete_occurrence.label'
                ]);

                $form->add("method", ChoiceType::class, [
                    "label" => "dende_calendar.form.method.label",
//                    'choices' => array_combine(UpdateEventHandler::$availableModes, array_map(function($mode) {
//                        return sprintf('dende_calendar.form.method.choice.%s', $mode);
//                    }, UpdateEventHandler::$availableModes)),
                    'choices' => [
                        OccurrenceUpdateManager::MODE_SINGLE         => 'dende_calendar.form.method.choice.single',
                        OccurrenceUpdateManager::MODE_ALL_INCLUSIVE  => 'dende_calendar.form.method.choice.allinclusive',
                        OccurrenceUpdateManager::MODE_NEXT_INCLUSIVE => 'dende_calendar.form.method.choice.nextinclusive',
                    ],
                    "data" => OccurrenceUpdateManager::MODE_NEXT_INCLUSIVE
                ]);
            } else {
                $form->remove('repetitions');
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
            'data_class' => UpdateFormData::class,
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
