<?php

namespace SmartCore\Module\Menu\Form\Tree;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\Type\DoctrineType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ItemTreeType extends DoctrineType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $loader = function (Options $options) {
            return $this->getLoader($options['em'], $options['query_builder'], $options['class']);
        };

        $resolver->setDefaults([
            'property'  => 'form_title',
            'loader'    => $loader,
            'class'     => 'MenuModule:Item',
        ]);
    }

    public function getLoader(ObjectManager $manager, $queryBuilder, $class)
    {
        return new ItemLoader($manager, $queryBuilder, $class);
    }

    public function getName()
    {
        return 'smart_module_menu_item_tree';
    }
}
