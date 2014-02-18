<?php

namespace SmartCore\Bundle\CMSBundle\Engine;

use Doctrine\ORM\EntityManager;
use SmartCore\Bundle\CMSBundle\Entity\Block;
use SmartCore\Bundle\CMSBundle\Form\Type\BlockFormType;
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
    protected $formFactory;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $repository;

    /**
     * @param EntityManager $em
     * @param FormFactoryInterface $form_factory
     */
    public function __construct(EntityManager $em, FormFactoryInterface $formFactory)
    {
        $this->em = $em;
        $this->formFactory = $formFactory;
        $this->repository  = $em->getRepository('CMSBundle:Block');
    }

    /**
     * @return Block[]
     */
    public function all()
    {
        $blocks = $this->repository->findBy([], ['position' => 'ASC']);

        if (empty($blocks)) {
            $this->update(new Block('content', 'Content workspace'));
            return $this->all();
        }

        return $blocks;
    }

    /**
     * @param null $name
     * @return Block
     */
    public function create($name = null, $descr = null)
    {
        return new Block($name, $descr);
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
        return $this->formFactory->create(new BlockFormType(), $data, $options);
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
        $this->em->flush($entity);
    }

    /**
     * @param Block $entity
     */
    public function update(Block $entity)
    {
        $this->em->persist($entity);
        $this->em->flush($entity);
    }
}
