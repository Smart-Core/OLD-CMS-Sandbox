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
        // Запрос списка блоков, чтобы в случае отсутствия, был создан дефолтный блок.
        // @todo убрать отсюда.
        Container::get('cms.block')->all();

        $modules = [];
        foreach (Container::get('cms.module')->all() as $module_name => $_dummy) {
            $modules[$module_name] = $module_name;
        }

        $builder
            ->add('module', 'choice', [
                'choices' => $modules,
                'data' => 'Texter', // @todo настройку модуля по умолчанию.
            ])
            ->add('folder', 'cms_folder_tree')
            ->add('block', 'entity', [
                'class' => 'CMSBundle:Block',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('b')->orderBy('b.position', 'ASC');
                },
                'required' => true,
            ])
            ->add('template')
            ->add('descr')
            ->add('position')
            ->add('priority')
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
        return 'smart_core_node';
    }
}
