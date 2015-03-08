<?php

namespace SmartCore\Module\Unicat\Form\Type;

use SmartCore\Module\Unicat\Entity\UnicatStructure;
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
            ->add('is_default_inheritance', null, ['required' => false])
            ->add('is_tree',    null, ['required' => false])
            ->add('position')
            ->add('properties')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'SmartCore\Module\Unicat\Entity\UnicatStructure',
        ]);
    }

    public function getName()
    {
        return 'unicat_structure';
    }
}
