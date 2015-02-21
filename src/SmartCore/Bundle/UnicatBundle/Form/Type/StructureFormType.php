<?php

namespace SmartCore\Bundle\UnicatBundle\Form\Type;

use SmartCore\Bundle\UnicatBundle\Entity\UnicatStructure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StructureFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',      null, ['attr'  => ['autofocus' => 'autofocus']])
            ->add('title_form', null, ['label' => 'Title in forms'])
            ->add('name')
            ->add('entries', 'choice', [
                'choices' => UnicatStructure::getEntriesChoices(),
            ])
            ->add('is_required')
            ->add('is_default_inheritance')
            ->add('position')
            ->add('properties')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'SmartCore\Bundle\UnicatBundle\Entity\UnicatStructure',
        ]);
    }

    public function getName()
    {
        return 'smart_unicat_structure';
    }
}
