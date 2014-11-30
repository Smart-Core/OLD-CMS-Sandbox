<?php

namespace SmartCore\Bundle\MediaBundle\Provider;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use SmartCore\Bundle\MediaBundle\Entity\File;
use SmartCore\Bundle\MediaBundle\Entity\FileTransformed;
use SmartCore\Bundle\MediaBundle\Service\GeneratorService;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class LocalProvider implements ProviderInterface
{
    use ContainerAwareTrait;

    /**
     * @var string
     */
    //protected $sourceRoot = '%kernel.root_dir%/usr/media_cloud';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $filesRepo;

    /**
     * @var EntityRepository
     */
    protected $filesTransformedRepo;

    /**
     * @var GeneratorService
     */
    protected $generator;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @param EntityRepository $filesRepo
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container            = $container;
        $this->em                   = $container->get('doctrine.orm.entity_manager');
        $this->filesRepo            = $this->em->getRepository('SmartMediaBundle:File');
        $this->filesTransformedRepo = $this->em->getRepository('SmartMediaBundle:FileTransformed');
        $this->generator            = $container->get('smart_media.generator');
        $this->request              = $container->get('request_stack')->getCurrentRequest();
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

        if ($filter) {
            $fileTransformed = $this->filesTransformedRepo->findOneBy(['file' => $file, 'filter' => $filter]);

            if (null === $fileTransformed) {
                $imagine = $this->container->get('imagine');
                $imagineFilterManager = $this->container->get('imagine.filter.manager');

                if (strpos($file->getMimeType(), 'jpeg') == false or strpos($file->getMimeType(), 'png') == false) {
                    echo 'Unsupported image format';

                    return null;
                }

                $originalImage = $imagine->open(dirname($this->request->server->get('SCRIPT_FILENAME')) . $file->getFullRelativeUrl());

                $webDir = dirname($this->request->server->get('SCRIPT_FILENAME')) . $this->generator->generateRelativePath($file, $filter);
                if (!is_dir($webDir) and false === @mkdir($webDir, 0777, true)) {
                    throw new \RuntimeException(sprintf("Unable to create the %s directory.\n", $webDir));
                }

                $transformedImagePath = $webDir . '/' . $file->getFilename();

                $transformedImage = $imagineFilterManager->getFilter($filter)->apply($originalImage);
                $transformedImage->save($transformedImagePath);

                $fileTransformed = new FileTransformed();
                $fileTransformed
                    ->setFile($file)
                    ->setFilter($filter)
                    ->setSize((new \SplFileInfo($transformedImagePath))->getSize())
                ;

                $this->em->persist($fileTransformed);
                $this->em->flush($fileTransformed);
            }
        }

        return $this->request->getBasePath() . $file->getFullRelativeUrl($filter);
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

        $newFile = $file->getUploadedFile()->move($webDir, $file->getFilename());

        // @todo настройка качества сжатия и условное уменьшение т.е. если картинка больше заданных размеров.
        // @todo возможность использовать Imagic, если доступен.
        if (strpos($newFile->getMimeType(), 'jpeg') !== false) {
            $img = imagecreatefromjpeg($newFile->getPathname());
            imagejpeg($img, $newFile->getPathname(), 90);
            imagedestroy($img);

            clearstatcache();

            $file->setSize($newFile->getSize());
        }

        return $newFile;
    }

    /**
     * @param int $id
     * @return bool
     *
     * @todo качественную обработку ошибок.
     */
    public function remove($id)
    {
        $filesTransformed = $this->filesTransformedRepo->findBy(['file' => $id]);

        /** @var FileTransformed $fileTransformed */
        foreach ($filesTransformed as $fileTransformed) {
            $fullPath = dirname($this->request->server->get('SCRIPT_FILENAME')) . $fileTransformed->getFullRelativeUrl();

            if (file_exists($fullPath)) {
                @unlink($fullPath);
            }
        }

        // Удаление оригинала.
        if (!empty($fileTransformed) and $fileTransformed instanceof FileTransformed) {
            $fullPath = dirname($this->request->server->get('SCRIPT_FILENAME')) . $fileTransformed->getFile()->getFullRelativeUrl();

            return @unlink($fullPath);
        }

        return true;
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
