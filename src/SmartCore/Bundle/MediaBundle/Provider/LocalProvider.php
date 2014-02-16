<?php

namespace SmartCore\Bundle\MediaBundle\Provider;

use Doctrine\ORM\EntityRepository;
use SmartCore\Bundle\MediaBundle\Entity\File;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class LocalProvider implements ProviderInterface
{
    /**
     * @var string
     */
    //protected $sourceRoot = '%kernel.root_dir%/usr/media_cloud';

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
        if (null === $id) {
            return null;
        }

        /** @var File $file */
        $file = $this->filesRepo->find($id);

        if (null === $file) {
            return null;
        }

        return $this->request->getBasePath() . $file->getFullRelativeUrl();
    }

    /**
     * @param File $file
     * @return \Symfony\Component\HttpFoundation\File\File|void
     * @throws \RuntimeException
     */
    public function upload(File $file)
    {
        $webDir = dirname($this->request->server->get('SCRIPT_FILENAME')) . $file->getFullRelativePath();

        if (!is_dir($webDir) and false === @mkdir($webDir, 0777, true)) {
            throw new \RuntimeException(sprintf("Unable to create the %s directory.\n", $webDir));
        }

        return $file->getUploadedFile()->move($webDir, $file->getFilename());
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
