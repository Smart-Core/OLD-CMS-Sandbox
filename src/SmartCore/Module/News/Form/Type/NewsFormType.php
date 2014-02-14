<?php

namespace SmartCore\Module\News\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, ['attr' => ['class' => 'focused']])
            ->add('slug')
            ->add('annotation')
            ->add('text',  null, ['attr' => ['class' => 'wysiwyg', 'data-theme' => 'advanced']])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'SmartCore\Module\News\Entity\News',
        ]);
    }

    public function getName()
    {
        return 'smart_module_news_item';
    }
}
