<?php

namespace SmartCore\Module\Slider\Form\Type;

use SmartCore\Bundle\CMSBundle\Module\AbstractNodePropertiesFormType;
use Symfony\Component\Form\FormBuilderInterface;

class NodePropertiesFormType extends AbstractNodePropertiesFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, ['attr' => ['class' => 'focused']])
        ;
    }

    public function getName()
    {
        return 'smart_module_slider_node_properties';
    }
}
