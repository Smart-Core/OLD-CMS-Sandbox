<?php

namespace SmartCore\Module\Menu\Form\Type;

use SmartCore\Bundle\CMSBundle\Module\AbstractNodePropertiesFormType;
use Symfony\Component\Form\FormBuilderInterface;
use SmartCore\Bundle\CMSBundle\Container;

class NodePropertiesFormType extends AbstractNodePropertiesFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $groups = [];
        foreach (Container::get('doctrine.orm.default_entity_manager')->getRepository('MenuModule:Group')->findAll() as $group) {
            $groups[$group->getId()] = $group;
        }

        $builder
            ->add('depth', 'integer', [
                'attr' => ['class' => 'focused'],
                'required' => false,
            ])
            ->add('css_class', 'text', [
                'required' => false,
            ])
            ->add('selected_inheritance', 'checkbox', [
                'required' => false,
            ])
            ->add('group_id', 'choice', [
                'choices' => $groups,
            ])
        ;
    }

    public function getName()
    {
        return 'smart_module_menu_node_properties';
    }
}
