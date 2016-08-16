<?php
namespace Dende\CalendarBundle\Form\Type;

use Dende\Calendar\Application\Command\CreateEventCommand;
use Dende\Calendar\Domain\Calendar\Event\EventType;
use Dende\Calendar\Domain\Calendar\Event\Repetitions;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateEventType extends AbstractType
{
    use UpdateNameTrait;

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("calendar", EntityType::class, [
                "required" => false,
                "class" => "Calendar:Calendar",
                "choice_label" => "name",
                "em" => $options["model_manager_name"],
                "label" => "dende_calendar.form.calendar.label",
                'placeholder'=> "dende_calendar.form.calendar.placeholder"
            ])
            ->add("new_calendar_name", TextType::class, [
                "mapped" => false,
                "required" => false,
                "label" => "dende_calendar.form.new_calendar_name.label"
            ])
            ->add("type", ChoiceType::class, [
                "choices" => array_combine(
                    EventType::$availableTypes,
                    array_map($this->updateNames('type'), EventType::$availableTypes)
                ),
                "label" => "dende_calendar.form.type.label"
            ])
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
                "label" => "dende_calendar.form.end_date.label"
            ])
            ->add("duration", IntegerType::class, [
                "label" => "dende_calendar.form.duration.label"
            ])
            ->add("title", "text", [
                "label" => "dende_calendar.form.title.label"
            ])
            ->add("repetitionDays", ChoiceType::class, [
                "choices" => array_map($this->updateNames('repetition_days'), Repetitions::$availableWeekdays),
                "multiple" => true,
                "expanded" => true,
                "label" => "dende_calendar.form.repetition_days.label"
            ])
            ->add("submit", SubmitType::class, [
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CreateEventCommand::class,
            'model_manager_name' => 'default',
        ]);

        $resolver->setAllowedTypes([
            'model_manager_name' => 'string',
        ]);
    }
}
