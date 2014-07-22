<?php

namespace SmartCore\Bundle\CMSBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class HtmlEntitiesViewTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        return htmlentities($value);
    }

    public function reverseTransform($value)
    {
        return $value;
    }
}
