<?php

namespace SmartCore\Bundle\CMSBundle\Form\Type;

use SmartCore\Bundle\CMSBundle\Form\DataTransformer\HtmlTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SettingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add($builder
                ->create('value', 'text', ['attr' => ['class' => 'focused']])
                ->addViewTransformer(new HtmlTransformer(false))
            )
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'SmartCore\Bundle\CMSBundle\Entity\Setting',
        ]);
    }

    public function getName()
    {
        return 'smart_core_cms_setting';
    }
}
