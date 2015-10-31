<?php
namespace Dende\CalendarBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

final class CalendarType extends AbstractType
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
            ->add("new_calendar_name", "text", [
                "mapped" => false,
                "required" => false
            ])
        ;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'calendar';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dende\Calendar\Domain\Calendar'
        ));
    }
}
