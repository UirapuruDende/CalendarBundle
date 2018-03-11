<?php
namespace Dende\CalendarBundle\Form\Type;

use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\Event\EventType;
use Dende\CalendarBundle\DTO\CreateFormData;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateEventType extends AbstractEventType
{
    use UpdateNameTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
        ->add('calendar', EntityType::class, [
            'required'     => false,
            'class'        => Calendar::class,
            'choice_label' => 'title',
            'choice_value' => 'id',
            'em'           => $options['model_manager_name'],
            'label'        => 'dende_calendar.form.calendar.label',
            'placeholder'  => 'dende_calendar.form.calendar.placeholder',
        ])
        ->add('newCalendarName', TextType::class, [
            'required' => false,
            'label'    => 'dende_calendar.form.new_calendar_name.label',
        ])
        ->add('type', ChoiceType::class, [
            'choices' => array_combine(
                array_map($this->updateNames('type'), EventType::$availableTypes),
                EventType::$availableTypes
            ),
            'label' => 'dende_calendar.form.type.label',
        ]);

        $builder->get('type')->addModelTransformer(new CallbackTransformer(
            function (EventType $type) {
                return $type->type();
            }, function (string $type) {
                return new EventType($type);
            }
        ));
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class'         => CreateFormData::class,
            'model_manager_name' => 'default',
            'validation_groups'  => function (FormInterface $form) {
                $validationGroups = ['Default'];

               /** @var CreateFormData $data */
               $data = $form->getData();

                if (is_null($data->calendar()) && is_null($data->newCalendarName())) {
                    $validationGroups[] = 'createNewCalendar';
                }

                if ($data->type() === EventType::TYPE_WEEKLY) {
                    $validationGroups[] = 'weeklyEvent';
                }

                return $validationGroups;
            },
       ]);

        $resolver->setAllowedTypes('model_manager_name', 'string');
    }

    public function getBlockPrefix()
    {
        return $this->getName();
    }


    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'create_event';
    }
}
