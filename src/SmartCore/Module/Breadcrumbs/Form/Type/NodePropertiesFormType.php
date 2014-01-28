<?php

namespace SmartCore\Module\Breadcrumbs\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NodePropertiesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('delimiter', 'text', ['attr' => ['class' => 'focused']]) // Разделитель
            ->add('hide_if_only_home', 'checkbox')  // Скрыть, если выбрана корневая папка
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(['csrf_protection' => false]);
    }

    public function getName()
    {
        return 'breadcrumbs_node_properties';
    }
}
