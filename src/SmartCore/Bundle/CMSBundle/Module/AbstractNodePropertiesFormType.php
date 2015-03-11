<?php

namespace SmartCore\Bundle\CMSBundle\Module;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

abstract class AbstractNodePropertiesFormType extends AbstractType
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }

    /**
     * @param string $entityName
     * @return array
     */
    protected function getChoicesByEntity($entityName)
    {
        $choices = [];
        foreach ($this->em->getRepository($entityName)->findAll() as $choice) {
            $choices[$choice->getId()] = $choice;
        }

        return $choices;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return '@CMS/AdminStructure/node_properties_form.html.twig';
    }
}
