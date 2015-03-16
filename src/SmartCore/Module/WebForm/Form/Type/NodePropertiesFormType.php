<?php

namespace SmartCore\Module\WebForm\Form\Type;

use SmartCore\Bundle\CMSBundle\Module\AbstractNodePropertiesFormType;
use Symfony\Component\Form\FormBuilderInterface;

class NodePropertiesFormType extends AbstractNodePropertiesFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('some_value')
        ;
    }

    public function getName()
    {
        return 'web_form_node_properties';
    }
}
