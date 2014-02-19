<?php

namespace SmartCore\Bundle\UnicatBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CategoryPropertiesFormType extends AbstractType
{
    protected $properties;

    public function __construct($properties)
    {
        $this->properties = $properties;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($this->properties as $name => $type) {
            if ('image' === $type) {
                $type = new PropertyImageFormType();
            }

            $builder->add($name, $type, [
                'required' => false,
            ]);
        }
    }

    public function getName()
    {
        return 'smart_unicat_category_properties';
    }
}
