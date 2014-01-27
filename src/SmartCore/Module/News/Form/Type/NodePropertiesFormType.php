<?php

namespace SmartCore\Module\News\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use SmartCore\Bundle\CMSBundle\Container;

class NodePropertiesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('items_per_page', 'integer', ['attr' => ['class' => 'focused']])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }

    public function getName()
    {
        return 'smart_module_news_node_properties';
    }
}
