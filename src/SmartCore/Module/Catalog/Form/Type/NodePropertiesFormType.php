<?php

namespace SmartCore\Module\Catalog\Form\Type;

use SmartCore\Bundle\CMSBundle\Container;
use SmartCore\Bundle\CMSBundle\Module\AbstractNodePropertiesFormType;
use Symfony\Component\Form\FormBuilderInterface;

class NodePropertiesFormType extends AbstractNodePropertiesFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $repositories = [];
        foreach (Container::get('doctrine.orm.default_entity_manager')->getRepository('UnicatBundle:UnicatRepository')->findAll() as $repo) {
            $repositories[$repo->getId()] = $repo;
        }

        $builder
            ->add('repository_id', 'choice', [
                'choices' => $repositories,
                'required' => false,
            ])
        ;
    }

    public function getName()
    {
        return 'smart_module_catalog_node_properties';
    }
}
