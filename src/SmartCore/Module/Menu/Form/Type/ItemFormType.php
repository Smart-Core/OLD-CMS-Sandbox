<?php

namespace SmartCore\Module\Menu\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ItemFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('is_active')
            ->add('parent_item', 'entity', [
                'class' => 'MenuModule:Item',
                'attr' => ['class' => 'input-block-level'],
                'required' => false,
            ])
            ->add('folder', 'folder_tree', [
                'required' => false,
            ])
            ->add('title', null, [
                'attr' => ['class' => 'focused'],
            ])
            ->add('url')
            ->add('descr')
            ->add('position')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'SmartCore\Module\Menu\Entity\Item',
        ]);
    }

    public function getName()
    {
        return 'sc_menu_item';
    }
}
