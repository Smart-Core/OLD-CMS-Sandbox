<?php

namespace SmartCore\Module\SimpleNews\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageFormType extends AbstractType
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
        return 'smart_module_simple_news_image';
    }
}
