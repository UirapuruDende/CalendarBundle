<?php
namespace Dende\CalendarBundle\Form\Type;

use Dende\Calendar\Domain\Calendar\Event;
use Dende\Calendar\Domain\Calendar\Event\EventType;
use Dende\Calendar\Domain\Calendar\Event\Repetitions;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

final class CreateEventType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("calendar", "entity", [
                "class" => "Dende\Calendar\Domain\Calendar",
                "choice_label" => "name",
////                "placeholder" => "Choose calendar",
            ])
            ->add("type", "choice", [
                "choices" => EventType::$availableTypes,
//                "placeholder" => "Choose event type"
            ])
            ->add("startDate", "datetime")
            ->add("endDate", "datetime")
            ->add("duration", "integer")
            ->add("title", "text", ["data" => 'test'])
            ->add("repetitionDays", "choice", [
                "choices" => Repetitions::$availableWeekdays,
                "multiple" => true,
                "expanded" => true
            ])
            ->add("submit", "submit")
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
        $resolver->setDefaults(array(
            'data_class' => 'Dende\Calendar\Application\Command\CreateEventCommand'
        ));
    }
}
