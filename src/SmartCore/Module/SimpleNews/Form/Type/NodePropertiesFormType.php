<?php

namespace SmartCore\Module\SimpleNews\Form\Type;

use SmartCore\Bundle\CMSBundle\Module\AbstractNodePropertiesFormType;
use Symfony\Component\Form\FormBuilderInterface;

class NodePropertiesFormType extends AbstractNodePropertiesFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('items_per_page', 'integer', ['attr' => ['autofocus' => 'autofocus']])
        ;
    }

    public function getName()
    {
        return 'smart_module_news_node_properties';
    }
}
