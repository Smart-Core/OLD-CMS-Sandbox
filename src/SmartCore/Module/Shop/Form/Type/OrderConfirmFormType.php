<?php

namespace SmartCore\Module\Shop\Form\Type;

use SmartCore\Module\Shop\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
            ->add('shipping', null, ['required' => true])
            ->add('address')
            ->add('comment')
            ->add('confirm', SubmitType::class, [
                'attr' => ['class' => 'btn btn-success'],
                'label' => 'Оформить заказ',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'smart_shop_order_confirm';
    }
}
