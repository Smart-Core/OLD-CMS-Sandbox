<?php

namespace SmartCore\Module\WebForm\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WebFormSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, ['attr'  => ['autofocus' => 'autofocus']])
            ->add('name')
            ->add('is_use_captcha', null, ['required' => false])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'SmartCore\Module\WebForm\Entity\WebForm',
        ]);
    }

    public function getName()
    {
        return 'smart_module_webform_settings';
    }
}
