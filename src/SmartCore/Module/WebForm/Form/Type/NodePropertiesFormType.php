<?php

namespace SmartCore\Module\WebForm\Form\Type;

use SmartCore\Bundle\CMSBundle\Module\AbstractNodePropertiesFormType;
use Symfony\Component\Form\FormBuilderInterface;

class NodePropertiesFormType extends AbstractNodePropertiesFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $webforms = [];
        foreach ($this->em->getRepository('WebFormModule:WebForm')->findAll() as $webform) {
            $webforms[$webform->getId()] = $webform;
        }

        $builder
            ->add('webform_id', 'choice', [
                'choices'  => $webforms,
                'required' => false,
                'label'    => 'WebForms',
            ])
        ;
    }

    public function getName()
    {
        return 'web_form_node_properties';
    }
}
