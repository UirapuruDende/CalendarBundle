<?php
namespace Dende\CalendarBundle\Form\Type;

use Dende\Calendar\Application\Command\CreateEventCommand;
use Dende\Calendar\Application\Command\EventCommandInterface;
use Dende\Calendar\Application\Command\UpdateEventCommand;
use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\Event\EventType;
use Dende\Calendar\Domain\Calendar\Event\Repetitions;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractEventType extends AbstractType
{
    use UpdateNameTrait;

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("startDate", DateTimeType::class, [
                'widget' => 'single_text',
                'with_seconds' => false,
                'format' => 'Y-M-dd HH:mm',
                'attr' => [
                    'class' => 'form_datetime'
                ],
                "label" => "dende_calendar.form.start_date.label"
            ])
            ->add("endDate", DateTimeType::class, [
                'widget' => 'single_text',
                'with_seconds' => false,
                'format' => 'Y-M-dd HH:mm',
                'attr' => [
                    'class' => 'form_datetime'
                ],
                "label" => "dende_calendar.form.end_date.label",
            ])
            ->add("title", TextType::class, [
                "label" => "dende_calendar.form.title.label"
            ])
            ->add("repetitionDays", ChoiceType::class, [
                "choices" => array_map($this->updateNames('repetition_days'), Repetitions::$availableWeekdays),
                "multiple" => true,
                "expanded" => true,
                "label" => "dende_calendar.form.repetition_days.label"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EventCommandInterface::class,
            'model_manager_name' => 'default',
            'validation_groups' => function(FormInterface $form){
                $validationGroups = ['Default'];

                /** @var UpdateEventCommand|CreateEventCommand $command */
                $command = $form->getData();

                if(get_class($command) === CreateEventCommand::class) {
                    if(is_null($command->calendar) && is_null($command->newCalendarName)) {
                        $validationGroups[] = 'createNewCalendar';
                    }

                    if($command->type === EventType::TYPE_WEEKLY) {
                        $validationGroups[] = 'weeklyEvent';
                    }
                } elseif(get_class($command) === UpdateEventCommand::class) {
                    if($command->occurrence->event()->isWeekly()) {
                        $validationGroups[] = 'weeklyEvent';
                    }
                }

                return $validationGroups;
            }
        ]);

        $resolver->setAllowedTypes([
            'model_manager_name' => 'string',
        ]);
    }
}