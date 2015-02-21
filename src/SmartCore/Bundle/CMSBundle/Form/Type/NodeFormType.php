<?php

namespace SmartCore\Bundle\CMSBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use SmartCore\Bundle\CMSBundle\Container;
use SmartCore\Bundle\CMSBundle\Entity\Node;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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

        $moduleThemes = [];
        foreach (Container::get('cms.module')->getThemes($options['data']->getModule().'Module') as $theme) {
            $moduleThemes[$theme] = $theme;
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
            ->add('controls_in_toolbar', 'choice', [
                'choices' => [
                    Node::TOOLBAR_NO => 'Никогда',
                    Node::TOOLBAR_ONLY_IN_SELF_FOLDER => 'Только в собственной папке',
                    //Node::TOOLBAR_ALWAYS => 'Всегда', // @todo
                ],
            ])
            ->add('template', 'choice', [
                'choices'  => $moduleThemes,
                'required' => false,
                'label'    => 'Тема шаблонов',
            ])
            ->add('description')
            ->add('position')
            ->add('priority')
            ->add('is_active', null, ['required' => false])
            ->add('is_cached', null, ['required' => false])
        ;

        if (empty($moduleThemes)) {
            $builder->remove('template');
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'SmartCore\Bundle\CMSBundle\Entity\Node',
        ]);
    }

    public function getName()
    {
        return 'smart_core_cms_node';
    }
}
