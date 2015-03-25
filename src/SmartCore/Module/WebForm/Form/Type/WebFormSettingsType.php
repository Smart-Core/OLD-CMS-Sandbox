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
            ->add('is_ajax', null, ['required' => false])
            ->add('is_use_captcha', null, ['required' => false])
            ->add('send_button_title')
            ->add('send_notice_emails')
            ->add('from_email')
            ->add('final_text')
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
