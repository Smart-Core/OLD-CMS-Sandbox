<?php

namespace SmartCore\Bundle\SettingsBundle\Form\Type;

use Smart\CoreBundle\Form\DataTransformer\HtmlTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SettingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add($builder
                ->create('value', 'text', ['attr' => ['autofocus' => 'autofocus']])
                ->addViewTransformer(new HtmlTransformer(false))
            )
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'SmartCore\Bundle\SettingsBundle\Entity\Setting',
        ]);
    }

    public function getName()
    {
        return 'smart_core_settings';
    }
}
