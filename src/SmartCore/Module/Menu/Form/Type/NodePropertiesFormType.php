<?php

namespace SmartCore\Module\Menu\Form\Type;

use SmartCore\Bundle\CMSBundle\Module\AbstractNodePropertiesFormType;
use Symfony\Component\Form\FormBuilderInterface;

class NodePropertiesFormType extends AbstractNodePropertiesFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('depth',          'integer',  ['attr' => ['autofocus' => 'autofocus'], 'required' => false])
            ->add('css_class',      'text',     ['required' => false])
            ->add('current_class',  'text',     ['required' => false])
            ->add('selected_inheritance', 'checkbox', ['required' => false])
            ->add('group_id',       'choice',   ['choices' => $this->getChoicesByEntity('MenuModule:Group')])
        ;
    }

    public function getName()
    {
        return 'smart_module_menu_node_properties';
    }
}
