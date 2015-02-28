<?php

namespace SmartCore\Module\Unicat\Form\Tree;

use Doctrine\Common\Persistence\ObjectManager;
use SmartCore\Module\Unicat\Entity\UnicatStructure;
use Symfony\Bridge\Doctrine\Form\Type\DoctrineType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoryTreeType extends DoctrineType
{
    /**
     * @var UnicatStructure
     */
    protected $structure;

    /**
     * @param UnicatStructure $structure
     * @return $this
     */
    public function setStructure(UnicatStructure $structure)
    {
        $this->structure = $structure;

        return $this;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $loader = function (Options $options) {
            return $this->getLoader($options['em'], $options['query_builder'], $options['class']);
        };

        $resolver->setDefaults([
            'property'  => 'form_title',
            'loader'    => $loader,
            'class'     => $this->structure->getConfiguration()->getCategoryClass(),
            'required'  => false,
        ]);
    }

    public function getLoader(ObjectManager $manager, $queryBuilder, $class)
    {
        return new CategoryLoader($manager, $this->structure, $class);
    }

    public function getName()
    {
        return 'unicat_category_tree';
    }
}
