<?php

namespace SmartCore\Bundle\CMSBundle\Engine;

use SmartCore\Bundle\CMSBundle\Entity\Node;
use SmartCore\Bundle\CMSBundle\Form\Type\FolderFormType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use SmartCore\Bundle\CMSBundle\Entity\Folder;

class EngineFolder
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \SmartCore\Bundle\CMSBundle\Entity\FolderRepository
     */
    protected $repository;

    /**
     * @var \RickySu\Tagcache\Adapter\TagcacheAdapter
     */
    protected $cache;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container  = $container;
        $this->cache      = $container->get('tagcache');
        $this->em         = $container->get('doctrine.orm.entity_manager');
        $this->repository = $this->em->getRepository('CMSBundle:Folder');
    }

    /**
     * @return Folder
     */
    public function create()
    {
        return new Folder();
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
        return $this->container->get('form.factory')->create(new FolderFormType(), $data, $options);
    }

    /**
     * Поиск по родительской папке.
     *
     * @param Folder $parent_folder
     * @return Folder[]
     */
    public function findByParent(Folder $parent_folder = null)
    {
        return $this->repository->findByParent($parent_folder);
    }

    /**
     * Получение полной ссылки на папку, указав её id. Если не указать ид папки, то вернётся текущий путь.
     *
     * @param  mixed|null $data - Node, Folder, $folderId or NULL for current folder Id form cms.context.
     * @return string $uri
     */
    public function getUri($data = null)
    {
        if (null === $data) {
            $folder_id = $this->container->get('cms.context')->getCurrentFolderId();
        } elseif ($data instanceof Node) {
            $folder_id = $data->getFolderId();
        } elseif ($data instanceof Folder) {
            $folder_id = $data->getId();
        } elseif (intval($data)) {
            $folder_id = $data;
        } else {
            throw new \Exception('Unknown input type.');
        }

        $cache_key = md5('cms_folder.full_path.' . $folder_id);
        if (false == $uri = $this->cache->get($cache_key)) {
            $uri = '/';
            $uri_parts = [];

            /** @var $folder Folder */
            while ($folder_id != 1) {
                $folder = $this->repository->findOneBy([
                    'is_active'  => true,
                    'is_deleted' => false,
                    'folder_id'  => $folder_id,
                ]);

                if ($folder) {
                    $folder_id = $folder->getParentFolder()->getId();
                    $uri_parts[] = $folder->getUriPart();
                } else {
                    break;
                }
            }

            $uri_parts = array_reverse($uri_parts);
            foreach ($uri_parts as $value) {
                $uri .= $value . '/';
            }

            $this->cache->set($cache_key, $uri, ['folder']);
        }

        return $this->container->get('request')->getBaseUrl() . $uri;
    }

    /**
     * @param int $id
     * @return null|Folder
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Remove entity.
     *
     * @todo проверку зависимостей от нод и папок.
     */
    public function remove(Folder $entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

    /**
     * @param Folder $folder
     * @return $this
     */
    public function update(Folder $folder)
    {
        $this->em->persist($folder);
        $this->em->flush($folder);

        $uriPart = $folder->getUriPart();

        // Если не указан сегмент URI, тогда он устанавливается в ID папки.
        if (empty($uriPart) and $folder->getId() > 1) {
            $folder->setUriPart($folder->getId());
            $this->em->persist($folder);
            $this->em->flush($folder);
        }

        return $this;
    }
}
