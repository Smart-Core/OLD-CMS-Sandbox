<?php

namespace SmartCore\Module\Menu\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ItemPropertiesFormType extends AbstractType
{
    protected $properties;

    public function __construct($properties)
    {
        $this->properties = $properties;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($this->properties as $name => $type) {
            $builder->add($name, $type, [
                'required' => false,
                'attr'     => ['class' => 'input-block-level'],
            ]);
        }
    }

    public function getName()
    {
        return 'smart_module_menu_item_properties';
    }
}
