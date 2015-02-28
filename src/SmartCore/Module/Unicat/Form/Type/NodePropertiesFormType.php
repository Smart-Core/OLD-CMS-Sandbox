<?php

namespace SmartCore\Module\Unicat\Form\Type;

use SmartCore\Bundle\CMSBundle\Module\AbstractNodePropertiesFormType;
use Symfony\Component\Form\FormBuilderInterface;

class NodePropertiesFormType extends AbstractNodePropertiesFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $configurations = [];
        foreach ($this->em->getRepository('UnicatModule:UnicatConfiguration')->findAll() as $configuration) {
            $configurations[$configuration->getId()] = $configuration;
        }

        $builder
            ->add('configuration_id', 'choice', [
                'choices'  => $configurations,
                'required' => false,
            ])
        ;
    }

    public function getName()
    {
        return 'smart_module_unicat_node_properties';
    }
}
