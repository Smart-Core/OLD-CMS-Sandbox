<?php

namespace SmartCore\Bundle\EngineBundle\Engine;

use Doctrine\ORM\EntityManager;
use SmartCore\Bundle\EngineBundle\Entity\Block;
use SmartCore\Bundle\EngineBundle\Form\Type\BlockFormType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormFactoryInterface;

class EngineBlock
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var FormFactoryInterface
     */
    protected $form_factory;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $repository;

    /**
     * @param EntityManager $em
     * @param FormFactoryInterface $form_factory
     */
    public function __construct(EntityManager $em, FormFactoryInterface $form_factory)
    {
        $this->em = $em;
        $this->form_factory = $form_factory;
        $this->repository = $em->getRepository('SmartCoreEngineBundle:Block');
    }

    /**
     * @return Block[]
     */
    public function all()
    {
        return $this->repository->findBy([], ['position' => 'ASC']);
    }

    /**
     * @return Block
     */
    public function create()
    {
        return new Block();
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
        return $this->form_factory->create(new BlockFormType(), $data, $options);
    }

    /**
     * @param integer $id
     * @return Block|null
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param Block $entity
     */
    public function remove(Block $entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

    /**
     * @param Block $entity
     */
    public function update(Block $entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }
}
