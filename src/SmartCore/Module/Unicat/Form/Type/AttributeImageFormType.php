<?php

namespace SmartCore\Module\Unicat\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AttributeImageFormType extends AbstractType
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
        return 'unicat_attribute_image';
    }
}
