<?php

namespace SmartCore\Module\Slider\Form\Type;

use SmartCore\Bundle\CMSBundle\Container;
use SmartCore\Bundle\CMSBundle\Module\AbstractNodePropertiesFormType;
use Symfony\Component\Form\FormBuilderInterface;

class NodePropertiesFormType extends AbstractNodePropertiesFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $sliders = [];
        foreach (Container::get('doctrine.orm.default_entity_manager')->getRepository('SliderModule:Slider')->findAll() as $slider) {
            $sliders[$slider->getId()] = $slider;
        }

        $builder
            ->add('slider_id', 'choice', ['choices' => $sliders, 'label' => 'Slider'])
        ;
    }

    public function getName()
    {
        return 'smart_module_slider_node_properties';
    }
}
