<?php

namespace SmartCore\Bundle\EngineBundle\Form\Type;

//use SmartCore\Bundle\EngineBundle\Form\EventListener\FolderSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FolderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, ['attr' => ['class' => 'focused']])
            ->add('uri_part')
            ->add('descr')
            ->add('parent_folder', 'folder_tree')
            ->add('position')
            ->add('is_active')
            ->add('is_file')
            ->add('has_inherit_nodes')
            ->add('template')
            //->add('permissions', 'text')
            //->add('lockout_nodes', 'text')
            //->addEventSubscriber(new FolderSubscriber())
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'SmartCore\Bundle\EngineBundle\Entity\Folder',
        ]);
    }

    public function getName()
    {
        return 'engine_folder';
    }
}
