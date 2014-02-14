<?php

namespace SmartCore\Bundle\MediaBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use SmartCore\Bundle\MediaBundle\Entity\Category;
use SmartCore\Bundle\MediaBundle\Entity\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\KernelInterface;

class CollectionService
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * @var EntityRepository
     */
    protected $collectionsRepo;

    /**
     * @var EntityRepository
     */
    protected $filesRepo;

    /**
     * @var EntityRepository
     */
    protected $storagesRepo;

    /**
     * @param EntityManager $em
     * @param KernelInterface $kernel
     */
    public function __construct(EntityManager $em, KernelInterface $kernel)
    {
        $this->em               = $em;
        $this->kernel           = $kernel;
        $this->collectionsRepo  = $em->getRepository('SmartMediaBundle:Collection');
        $this->filesRepo        = $em->getRepository('SmartMediaBundle:File');
        $this->storagesRepo     = $em->getRepository('SmartMediaBundle:Storage');
    }

    /**
     * @param UploadedFile $file
     * @param Category $category
     * @param array $tags
     * @return int - ID файла в коллекции.
     */
    public function createFile(UploadedFile $file, $category = null, array $tags = null)
    {
        // @todo
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteFile($id)
    {
        // @todo
    }

    /**
     * @param int|null $categoryId
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return File[]|null
     */
    public function getFilesList($categoryId = null, array $orderBy = null, $limit = null, $offset = null)
    {
        // @todo
    }

    /**
     * @param integer $id
     * @param array|null $transforms
     * @return string|null
     */
    public function getUriByFileId($id, array $transforms = null)
    {
        /** @var File $file */
        $file = $this->filesRepo->find($id);

        if (null === $file) {
            return null;
        }

        $fileUrl =
            $file->getStorage()->getBaseUrl() .
            $file->getCollection()->getRelativePath() .
            $file->getRelativePath() . '/' .
            $file->getFilename();

        $fileUrl = str_replace('{basePath}', $this->kernel->getContainer()->get('request')->getBasePath(), $fileUrl);

        return $fileUrl;
    }

    /**
     * @param int|null $categoryId
     */
    public function getCategories($categoryId = null)
    {
        // @todo подумать как лучше получать список категорий.
    }
}
