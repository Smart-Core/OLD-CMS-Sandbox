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
        // Запрос списка областей, чтобы в случае отсутствия, был создан дефолтная область.
        // @todo убрать отсюда.
        Container::get('cms.region')->all();

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
            ->add('region', 'entity', [
                'class' => 'CMSBundle:Region',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('b')->orderBy('b.position', 'ASC');
                },
                'required' => true,
            ])
            ->add('template')
            ->add('descr')
            ->add('position')
            ->add('priority')
            ->add('is_active', null, ['required' => false])
            ->add('is_cached', null, ['required' => false])
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
