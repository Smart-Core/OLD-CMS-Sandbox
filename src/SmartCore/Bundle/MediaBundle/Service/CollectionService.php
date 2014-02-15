<?php

namespace SmartCore\Bundle\MediaBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use SmartCore\Bundle\MediaBundle\Entity\Category;
use SmartCore\Bundle\MediaBundle\Entity\Collection;
use SmartCore\Bundle\MediaBundle\Entity\File;
use SmartCore\Bundle\MediaBundle\Provider\LocalProvider;
use SmartCore\Bundle\MediaBundle\Provider\ProviderInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CollectionService
{
    use ContainerAwareTrait;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $collectionsRepo;

    /**
     * @var EntityRepository
     */
    protected $filesRepo;

    /**
     * @var ProviderInterface
     */
    protected $provider;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container, $id = null)
    {
        $this->em               = $container->get('doctrine.orm.entity_manager');
        $this->collectionsRepo  = $this->em->getRepository('SmartMediaBundle:Collection');
        $this->collection       = $this->collectionsRepo->find($id);
        $this->filesRepo        = $this->em->getRepository('SmartMediaBundle:File');

        // @todo разные провайдеры.
        $this->provider = new LocalProvider($this->filesRepo, $container);
    }

    /**
     * Получить ссылку на файл.
     *
     * @param integer $id
     * @param string|null $filter
     * @return string|null
     */
    public function get($id, $filter = null)
    {
        return $this->provider->get($id, $filter);
    }

    /**
     * Получить ссылку на файл.
     *
     * @param integer $id
     * @param string|null $filter
     * @return string|null
     */
    public function getSplFile($id, $filter = null)
    {
        return $this->provider->getSplFile($id, $filter);
    }

    /**
     * @param UploadedFile $file
     * @param Category $category
     * @param array $tags
     * @return int - ID файла в коллекции.
     */
    public function upload(UploadedFile $uploadedFile, $category = null, array $tags = null)
    {
        $file = new File();
        $file
            ->setCollection($this->collection)
            ->setStorage($this->collection->getDefaultStorage())
            ->setFile($uploadedFile)
        ;

        $newFile = $this->provider->upload($file);

        $this->em->persist($file);
        $this->em->flush($file);

        return $file->getId();
    }

    /**
     * @param int $id
     * @return bool
     */
    public function remove($id)
    {
        // @todo
    }

    /**
     * Получить список файлов.
     *
     * @param int|null $categoryId
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return File[]|null
     */
    public function findBy($categoryId = null, array $orderBy = null, $limit = null, $offset = null)
    {
        // @todo
    }
}
