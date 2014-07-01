<?php

namespace SmartCore\Bundle\Unicat2Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RepositoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['attr'  => ['class' => 'focused']])
            ->add('title')
            ->add('is_inheritance')
            //->add('default_structure')
//            ->add('media_collection')
            ->add('entities_namespace')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'SmartCore\Bundle\Unicat2Bundle\Entity\UnicatRepository',
        ]);
    }

    public function getName()
    {
        return 'smart_unicat2_repository';
    }
}
