<?php

namespace SmartCore\Bundle\EngineBundle\Engine;

use Symfony\Component\DependencyInjection\ContainerInterface;

trait TraitEngine
{
    protected $__class__;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $repository;

    /**
     * Constructor.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->constructTrait($container);
    }

    /**
     * Trait constructor.
     */
    protected function constructTrait(ContainerInterface $container)
    {
        // get SmartCore\Bundle\EngineBundle\Engine\Engine{__CLASS__}
        $this->__class__ = substr(__CLASS__, strrpos(__CLASS__, '\\') + 7);
        $this->container = $container;
        $this->em = $container->get('doctrine.orm.entity_manager');
        $this->repository = $this->em->getRepository('SmartCoreEngineBundle:' . $this->__class__);
    }

    /**
     * Create entity.
     */
    public function create()
    {
        $entity_class = 'SmartCore\Bundle\EngineBundle\Entity\\' . $this->__class__;
        return new $entity_class();
    }

    /**
     * Creates and returns a Form instance from the type of the form.
     *
     * @param mixed $data    The initial data for the form
     * @param array $options Options for the form
     *
     * @return \Symfony\Component\Form\Form
     */
    public function createForm($data = null, array $options = [])
    {
        $form_class = 'SmartCore\Bundle\EngineBundle\Form\Type\\' . $this->__class__ . 'FormType';

        return $this->container->get('form.factory')->create(new $form_class(), $data, $options);
    }
}
