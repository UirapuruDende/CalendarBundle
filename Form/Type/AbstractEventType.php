<?php
namespace Dende\CalendarBundle\Form\Type;

use Dende\Calendar\Domain\Calendar\Event\Repetitions;
use Dende\CalendarBundle\Form\Type\UpdateEventType\RangeDatesType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AbstractEventType extends AbstractType
{
    use UpdateNameTrait;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('eventDates', RangeDatesType::class, [
                'label' => false,
            ])
            ->add('title', TextType::class, [
                'label' => 'dende_calendar.form.title.label',
            ])
            ->add('repetitions', ChoiceType::class, [
                'choices'  => array_map($this->updateNames('repetition_days'), Repetitions::$availableWeekdays),
                'multiple' => true,
                'expanded' => true,
                'label'    => 'dende_calendar.form.repetition_days.label',
            ]);

        $builder->get('repetitions')->addModelTransformer(new CallbackTransformer(
            function (Repetitions $repetitions) {
                return $repetitions->getArray();
            }, function (array $repetitions = []) {
                return new Repetitions($repetitions);
            }
        ));
    }
}
