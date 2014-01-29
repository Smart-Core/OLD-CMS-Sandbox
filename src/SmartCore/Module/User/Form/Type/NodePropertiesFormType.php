<?php

namespace SmartCore\Module\User\Form\Type;

use SmartCore\Bundle\CMSBundle\Module\AbstractNodePropertiesFormType;
use Symfony\Component\Form\FormBuilderInterface;

class NodePropertiesFormType extends AbstractNodePropertiesFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('allow_registration', 'checkbox', ['required' => false])
            ->add('allow_password_resetting', 'checkbox', ['required' => false])
        ;
    }

    public function getName()
    {
        return 'smart_module_user_node_properties';
    }
}
