<?php

namespace SmartCore\Bundle\CMSBundle\Engine;

use Doctrine\ORM\EntityManager;
use SmartCore\Bundle\CMSBundle\Entity\Region;
use SmartCore\Bundle\CMSBundle\Form\Type\RegionFormType;
use Symfony\Component\Form\FormFactoryInterface;

class EngineRegion
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
        $this->repository  = $em->getRepository('CMSBundle:Region');
    }

    /**
     * @return Region[]
     */
    public function all()
    {
        $regions = $this->repository->findBy([], ['position' => 'ASC']);

        if (empty($regions)) {
            $this->update(new Region('content', 'Content workspace'));

            return $this->all();
        }

        return $regions;
    }

    /**
     * @param string|null $name
     * @param string|null $descr
     *
     * @return Region
     */
    public function create($name = null, $descr = null)
    {
        return new Region($name, $descr);
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
        return $this->formFactory->create(new RegionFormType(), $data, $options);
    }

    /**
     * @param int $id
     *
     * @return Region|null
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param Region $entity
     */
    public function remove(Region $entity)
    {
        if ('content' == $entity->getName()) {
            return;
        }

        $this->em->remove($entity);
        $this->em->flush($entity);
    }

    /**
     * @param Region $entity
     */
    public function update(Region $entity)
    {
        $this->em->persist($entity);
        $this->em->flush($entity);
    }
}
