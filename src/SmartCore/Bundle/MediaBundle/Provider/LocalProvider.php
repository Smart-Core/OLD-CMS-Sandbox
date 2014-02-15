<?php

namespace SmartCore\Bundle\MediaBundle\Provider;

use Doctrine\ORM\EntityRepository;
use SmartCore\Bundle\MediaBundle\Entity\Collection;
use SmartCore\Bundle\MediaBundle\Entity\File;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class LocalProvider implements ProviderInterface
{
    /**
     * @var string
     */
    //protected $sourceRoot = '%kernel.root_dir%/usr/media_cloud';

    /**
     * @var string
     */
    //protected $webRoot = '%kernel.root_dir%/../web/_media';

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var EntityRepository
     */
    protected $filesRepo;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @param EntityRepository $filesRepo
     * @param ContainerInterface $container
     */
    public function __construct(EntityRepository $filesRepo, ContainerInterface $container)
    {
        $this->filesRepo = $filesRepo;
        $this->request   = $container->get('request_stack')->getCurrentRequest();
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
        /** @var File $file */
        $file = $this->filesRepo->find($id);

        if (null === $file) {
            return null;
        }

        return $this->request->getBasePath() . $file->getFullRelativeUrl();
    }

    /**
     * @param UploadedFile $file
     * @param int $category
     * @param array $tags
     * @return int - ID файла в коллекции.
     */
    public function upload(UploadedFile $file, $category = null, array $tags = null)
    {
        // @todo
    }

    /**
     * @param Collection $collection
     * @return $this
     */
    public function setCollection(Collection $collection)
    {
        $this->collection = $collection;

        return $this;
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
