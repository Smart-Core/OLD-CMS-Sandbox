<?php

namespace SmartCore\Module\Shop\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderConfirmFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('phone')
            ->add('name')
            ->add('shipping')
            ->add('address')
            ->add('comment')
            ->add('confirm', 'submit', [
                'attr' => ['class' => 'btn btn-success'],
                'label' => 'Оформить заказ',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'SmartCore\Module\Shop\Entity\Order',
        ]);
    }

    public function getName()
    {
        return 'smart_shop_order_confirm';
    }
}
