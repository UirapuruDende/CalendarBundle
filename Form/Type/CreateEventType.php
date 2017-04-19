<?php
namespace Dende\CalendarBundle\Form\Type;

use Dende\Calendar\Application\Command\CreateEventCommand;
use Dende\Calendar\Application\Repository\CalendarRepositoryInterface;
use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\Event\EventType;
use Dende\Calendar\Domain\Calendar\Event\Repetitions;
use Dende\CalendarBundle\DTO\CreateFormData;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateEventType extends AbstractEventType
{
    use UpdateNameTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add("calendar", EntityType::class, [
            "required" => false,
            "class" => Calendar::class,
            "choice_label" => "title",
            "choice_value" => "id",
            "em" => $options["model_manager_name"],
            "label" => "dende_calendar.form.calendar.label",
            'placeholder' => "dende_calendar.form.calendar.placeholder"
        ])
        ->add("newCalendarName", TextType::class, [
            "required" => false,
            "label" => "dende_calendar.form.new_calendar_name.label"
        ])
        ->add("type", ChoiceType::class, [
            "choices" => array_combine(
                EventType::$availableTypes,
                array_map($this->updateNames('type'), EventType::$availableTypes)
            ),
            "label" => "dende_calendar.form.type.label"
        ]);

        $builder->get('type')->addModelTransformer(new CallbackTransformer(
            function(EventType $type) {
                return $type->type();
            }, function (string $type) {
                return new EventType($type);
            }
        ));

    }


    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => CreateFormData::class,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'create_event';
    }
}
