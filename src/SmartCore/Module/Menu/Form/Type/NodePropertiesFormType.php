<?php

namespace SmartCore\Module\Menu\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use SmartCore\Bundle\EngineBundle\Container;

class NodePropertiesFormType extends AbstractType
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

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }

    public function getName()
    {
        return 'menu_node_properties';
    }
}
