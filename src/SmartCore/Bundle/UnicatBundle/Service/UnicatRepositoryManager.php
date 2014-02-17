<?php

namespace SmartCore\Bundle\UnicatBundle\Service;

use Doctrine\ORM\EntityManager;
use SmartCore\Bundle\UnicatBundle\Entity\UnicatRepository;
use SmartCore\Bundle\UnicatBundle\Model\ItemModel;

class UnicatRepositoryManager
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var UnicatRepository
     */
    protected $repository;

    /**
     * @param UnicatRepository $repository
     */
    public function __construct(EntityManager $em, UnicatRepository $repository)
    {
        $this->em         = $em;
        $this->repository = $repository;
    }

    /**
     * @return UnicatRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param array|null $orderBy
     * @return ItemModel|null
     */
    public function findAllItems($orderBy = null)
    {
        return $this->em->getRepository($this->repository->getItemClass())->findBy([], $orderBy);
    }
}
