<?php

namespace SmartCore\Bundle\CMSBundle\Form\Tree;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\Type\DoctrineType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FolderTreeType extends DoctrineType
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
            'class'     => 'CMSBundle:Folder',
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
