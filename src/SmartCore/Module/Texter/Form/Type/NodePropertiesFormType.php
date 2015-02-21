<?php

namespace SmartCore\Module\Texter\Form\Type;

use SmartCore\Bundle\CMSBundle\Module\AbstractNodePropertiesFormType;
use Symfony\Component\Form\FormBuilderInterface;

class NodePropertiesFormType extends AbstractNodePropertiesFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text_item_id', 'integer', ['attr' => ['autofocus' => 'autofocus']])
            ->add('editor', 'checkbox', ['required' => false])
        ;
    }

    public function getName()
    {
        return 'smart_module_texter_node_properties';
    }
}
