<?php

namespace SmartCore\Bundle\CMSBundle\Form\Type;

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
            ->add('folders', null, [
                //'attr' => ['style' => 'height: 300px;'],
                'expanded' => true,
                'label' => 'Inherit in folders',
                'required' => false,
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'SmartCore\Bundle\CMSBundle\Entity\Block',
        ]);
    }

    public function getName()
    {
        return 'engine_block';
    }
}
