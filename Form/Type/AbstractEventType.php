<?php
namespace Dende\CalendarBundle\Form\Type;

use Dende\Calendar\Application\Command\CreateEventCommand;
use Dende\Calendar\Application\Command\EventCommandInterface;
use Dende\Calendar\Application\Command\UpdateEventCommand;
use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\Event\EventType;
use Dende\Calendar\Domain\Calendar\Event\Repetitions;
use Dende\CalendarBundle\DTO\UpdateFormData;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
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
                'format' => 'Y-MM-dd HH:mm',
                'attr' => [
                    'class' => 'form_datetime'
                ],
                "label" => "dende_calendar.form.start_date.label"
            ])
            ->add("endDate", DateTimeType::class, [
                'widget' => 'single_text',
                'with_seconds' => false,
                'format' => 'Y-MM-dd HH:mm',
                'attr' => [
                    'class' => 'form_datetime'
                ],
                "label" => "dende_calendar.form.end_date.label",
            ])
            ->add("title", TextType::class, [
                "label" => "dende_calendar.form.title.label"
            ])
            ->add("repetitions", ChoiceType::class, [
                "choices" => array_map($this->updateNames('repetition_days'), Repetitions::$availableWeekdays),
                "multiple" => true,
                "expanded" => true,
                "label" => "dende_calendar.form.repetition_days.label"
            ])
        ;

        $builder->get('repetitions')->addModelTransformer(new CallbackTransformer(
            function(Repetitions $repetitions) {
                return $repetitions->getArray();
            }, function (array $repetitions = []) {
                return new Repetitions($repetitions);
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UpdateFormData::class,
            'model_manager_name' => 'default',
            'validation_groups' => function(FormInterface $form){
                $validationGroups = ['Default'];

                /** @var UpdateFormData $data */
                $data = $form->getData();

                if(is_null($data->calendar()) && is_null($data->newCalendarName())) {
                    $validationGroups[] = 'createNewCalendar';
                }

                if($data->type() === EventType::TYPE_WEEKLY) {
                    $validationGroups[] = 'weeklyEvent';
                }

                if(get_class($data) === UpdateEventCommand::class && !$data->occurrence()->event()->type()->isType($data->type())) {
                    $validationGroups[] = 'eventTypeChange';
                }

                return $validationGroups;
            }
        ]);

        $resolver->setAllowedTypes([
            'model_manager_name' => 'string',
        ]);
    }
}
