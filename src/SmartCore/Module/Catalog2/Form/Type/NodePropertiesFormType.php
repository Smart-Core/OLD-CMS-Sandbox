<?php

namespace SmartCore\Module\Catalog2\Form\Type;

use SmartCore\Bundle\CMSBundle\Container;
use SmartCore\Bundle\CMSBundle\Module\AbstractNodePropertiesFormType;
use Symfony\Component\Form\FormBuilderInterface;

class NodePropertiesFormType extends AbstractNodePropertiesFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $repositories = [];
        foreach (Container::get('unicat2')->allRepositories() as $repo) {
            $repositories[$repo->getId()] = $repo;
        }

        $builder
            ->add('repository_id', 'choice', [
                'choices'  => $repositories,
                'required' => false,
            ])
        ;
    }

    public function getName()
    {
        return 'smart_module_catalog2_node_properties';
    }
}
