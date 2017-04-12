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

class CreateEventType extends AbstractEventType
{
    use UpdateNameTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add("type", ChoiceType::class, [
            "choices" => array_combine(
                EventType::$availableTypes,
                array_map($this->updateNames('type'), EventType::$availableTypes)
            ),
            "label" => "dende_calendar.form.type.label"
        ]);

    }


    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => CreateEventCommand::class,
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
