<?php

namespace SmartCore\Module\Shop\Form\Type;

use SmartCore\Bundle\CMSBundle\Module\AbstractNodePropertiesFormType;
use Symfony\Component\Form\FormBuilderInterface;

class NodePropertiesFormType extends AbstractNodePropertiesFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('basket_node_id', null, ['required' => false, 'attr' => ['autofocus' => 'autofocus']])
            ->add('mode', 'choice', [
                'choices' => [
                    'basket_widget' => 'Basket widget',
                    'basket' => 'Basket full',
                ],
            ])
        ;
    }

    public function getName()
    {
        return 'shop_node_properties';
    }
}
