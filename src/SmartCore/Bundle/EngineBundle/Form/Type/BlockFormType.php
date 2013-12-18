<?php

namespace SmartCore\Bundle\EngineBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BlockFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['attr' => ['class' => 'focused']])
            ->add('descr')
            ->add('position')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'SmartCore\Bundle\EngineBundle\Entity\Block',
        ]);
    }

    public function getName()
    {
        return 'engine_block';
    }
}
