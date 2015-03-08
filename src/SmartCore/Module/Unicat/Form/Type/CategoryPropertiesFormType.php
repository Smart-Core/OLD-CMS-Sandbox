<?php

namespace SmartCore\Module\Unicat\Form\Type;

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
        foreach ($this->properties as $name => $options) {
            if ('image' === $options) {
                $type = new PropertyImageFormType();
            } elseif (isset($options['type'])) {
                $type = $options['type'];
            } else {
                $type = $options;
            }

            $builder->add($name, $type, [
                'required'  => false,
                'attr'      => isset($options['attr']) ? $options['attr'] : [],
            ]);
        }
    }

    public function getName()
    {
        return 'unicat_structure_properties';
    }
}
