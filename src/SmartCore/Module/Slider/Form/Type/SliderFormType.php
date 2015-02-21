<?php

namespace SmartCore\Module\Slider\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SliderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, ['attr' => ['autofocus' => 'autofocus']])
            ->add('width')
            ->add('height')
            ->add('pause_time')
            ->add('slide_properties')
            ->add('mode', 'choice', [
                'choices' => [
                    'INSET' => 'INSET',
                    'OUTBOUND' => 'OUTBOUND',
                ],
            ])
            ->add('library', 'choice', [
                'choices' => [
                    'jcarousel' => 'jcarousel',
                    'nivoslider' => 'nivoslider',
                ],
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'SmartCore\Module\Slider\Entity\Slider',
        ]);
    }

    public function getName()
    {
        return 'smart_module_slider';
    }
}
