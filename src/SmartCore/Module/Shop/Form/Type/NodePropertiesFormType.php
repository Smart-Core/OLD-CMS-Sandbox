<?php

namespace SmartCore\Module\Shop\Form\Type;

use SmartCore\Bundle\CMSBundle\Module\AbstractNodePropertiesFormType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class NodePropertiesFormType extends AbstractNodePropertiesFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('basket_node_id', null, ['required' => false, 'attr' => ['autofocus' => 'autofocus']])
            ->add('mode', ChoiceType::class, [
                'choices' => [
                    'Basket widget' => 'basket_widget',
                    'Basket full' => 'basket',
                    'My_orders' => 'my_orders',
                ],
            ])
        ;
    }

    public function getBlockPrefix()
    {
        return 'shop_node_properties';
    }
}
