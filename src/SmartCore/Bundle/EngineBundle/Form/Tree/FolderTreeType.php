<?php

namespace SmartCore\Bundle\EngineBundle\Form\Tree;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\Type\DoctrineType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FolderTreeType extends DoctrineType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $type = $this;

        $loader = function (Options $options) use ($type) {
            return $type->getLoader($options['em'], $options['query_builder'], $options['class']);
        };

        $resolver->setDefaults([
            'property'  => 'form_title',
            'loader'    => $loader,
            'class'     => 'SmartCoreEngineBundle:Folder',
            'attr'      => ['class' => 'input-block-level'],
        ]);
    }

    public function getLoader(ObjectManager $manager, $queryBuilder, $class)
    {
        return new FolderLoader($manager, $queryBuilder, $class);
    }

    public function getName()
    {
        return 'folder_tree';
    }
}
