<?php

namespace SmartCore\Bundle\CMSBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use SmartCore\Bundle\CMSBundle\Container;

class NodeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $modules = [];
        foreach (Container::get('cms.module')->all() as $module_name => $_dummy) {
            $modules[$module_name] = $module_name;
        }

        $builder
            ->add('module', 'choice', [
                'choices' => $modules,
                'data' => 'Texter',
                'attr' => ['class' => 'input-block-level'],
            ])
            ->add('folder', 'folder_tree')
            ->add('block', 'entity', [
                'class' => 'CMSBundle:Block',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('b')->orderBy('b.position', 'ASC');
                },
                'attr' => ['class' => 'input-block-level'],
                'required' => true,
            ])
            ->add('descr')
            ->add('position')
            ->add('is_active')
            ->add('is_cached')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'SmartCore\Bundle\CMSBundle\Entity\Node',
        ]);
    }

    public function getName()
    {
        return 'engine_node';
    }
}
