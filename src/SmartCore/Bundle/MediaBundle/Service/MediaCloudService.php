<?php

namespace SmartCore\Bundle\MediaBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MediaCloudService
{
    use ContainerAwareTrait;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param $id
     * @return CollectionService
     */
    public function getCollection($id)
    {
        return new CollectionService($this->container, $id);
    }


    public function createCollection()
    {
        // @todo
    }

    public function removeCollection()
    {
        // @todo
    }

    public function getCollectionsList()
    {
        // @todo
    }

    public function getStoragesList()
    {
        // @todo
    }

    public function createStorage()
    {
        // @todo
    }

    public function removeStorage()
    {
        // @todo
    }

    public function updateStorage()
    {
        // @todo
    }
}
