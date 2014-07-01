<?php

namespace SmartCore\Bundle\Unicat2Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PropertyImageFormType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }

    public function getParent()
    {
        return 'file';
    }

    public function getName()
    {
        return 'smart_unicat2_property_image';
    }
}
