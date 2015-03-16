<?php

namespace SmartCore\Module\WebForm\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WebFormFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, ['attr' => ['autofocus' => 'autofocus', 'placeholder' => 'Произвольная строка']])
            ->add('name',  null, ['attr' => ['placeholder' => 'Латинские буквы в нижем регистре и символы подчеркивания.']])
            ->add('type', 'choice', [
                'choices' => [
                    'text'        => 'Text',
                    'textarea'    => 'Textarea',
                    'email'       => 'Email',
                    'url'         => 'URL',
                    'date'        => 'Date',
                    'datetime'    => 'Datetime',
                    'checkbox'    => 'Сheckbox',
                    'select'      => 'Select',
                    'multiselect' => 'Multiselect',
                ],
            ])
            ->add('params_yaml',   null, ['attr' => ['data-editor' => 'yaml']])
            ->add('position')
            ->add('is_enabled',    null, ['required' => false])
            ->add('is_required',   null, ['required' => false])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'SmartCore\Module\WebForm\Entity\WebFormField',
        ]);
    }

    public function getName()
    {
        return 'smart_module_webform_field';
    }
}
