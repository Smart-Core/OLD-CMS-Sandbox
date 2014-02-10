<?php

namespace SmartCore\Module\Slider\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SlideEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('enabled')
            ->add('title', null, ['attr' => ['class' => 'focused']])
            ->add('position')
            ->add('update', 'submit', ['attr' => ['class' => 'btn btn-success']])
            ->add('delete', 'submit', ['attr' => ['class' => 'btn btn-danger', 'onclick' => "return confirm('Вы уверены, что хотите удалить слайд?')"]])
            ->add('cancel', 'submit', ['attr' => ['class' => 'btn']])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'SmartCore\Module\Slider\Entity\Slide',
        ]);
    }

    public function getName()
    {
        return 'smart_module_slider_item_edit';
    }
}
