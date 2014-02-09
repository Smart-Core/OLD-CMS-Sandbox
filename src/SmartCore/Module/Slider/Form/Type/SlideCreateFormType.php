<?php

namespace SmartCore\Module\Slider\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SlideCreateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', null, ['required' => false])
            ->add('title')
            ->add('position')
            ->add('upload', 'submit', ['attr' => ['class' => 'btn btn-success']])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'SmartCore\Module\Slider\Entity\Slide',
        ]);
    }

    public function getName()
    {
        return 'smart_module_slider_item_create';
    }
}
