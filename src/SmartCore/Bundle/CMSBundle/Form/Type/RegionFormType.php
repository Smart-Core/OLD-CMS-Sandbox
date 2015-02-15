<?php

namespace SmartCore\Bundle\CMSBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['attr' => ['class' => 'focused']])
            ->add('description')
            ->add('position')
            ->add('folders', 'cms_folder_tree', [
                //'attr'        => ['style' => 'height: 300px;'],
                'only_active' => true,
                'expanded'    => true,
                'multiple'    => true,
                'label'       => 'Inherit in folders',
                'required'    => false,
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'SmartCore\Bundle\CMSBundle\Entity\Region',
        ]);
    }

    public function getName()
    {
        return 'smart_core_cms_region';
    }
}
