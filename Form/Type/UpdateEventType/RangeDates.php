<?php
namespace Dende\CalendarBundle\Form\Type\UpdateEventType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RangeDates extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                "startDate", 'datetime', [
                'widget'       => 'single_text',
                'with_seconds' => false,
                'format'       => 'Y-MM-dd HH:mm',
                'attr'         => [
                    'class' => 'form_datetime'
                ],
                "label"        => "dende_calendar.form.start_date.label"
            ]
            )
            ->add(
                "endDate", 'datetime', [
                'widget'       => 'single_text',
                'with_seconds' => false,
                'format'       => 'Y-MM-dd HH:mm',
                'attr'         => [
                    'class' => 'form_datetime'
                ],
                "label"        => "dende_calendar.form.end_date.label",
            ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'range_dates';
    }

    /**
     * @param OptionsResolver $resolver
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
