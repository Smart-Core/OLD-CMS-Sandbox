<?php

namespace SmartCore\Bundle\MediaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CollectionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, ['attr' => ['autofocus' => 'autofocus']])
            ->add('relative_path')
            ->add('file_relative_path_pattern')
            ->add('filename_pattern')
            ->add('default_storage')
        ;

        return $builder;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'SmartCore\Bundle\MediaBundle\Entity\Collection',
        ]);
    }

    public function getName()
    {
        return 'smart_media_storage';
    }
}
