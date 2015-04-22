<?php

namespace SmartCore\Bundle\CMSBundle\Twig;

use SmartCore\Bundle\CMSBundle\Entity\Region;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CmsExtension extends \Twig_Extension
{
    use ContainerAwareTrait;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return [
            'cms_current_folder'        => new \Twig_Function_Method($this, 'getCurrentFolder'),
            'cms_folder_path'           => new \Twig_Function_Method($this, 'generateFolderPath'),
            'cms_nodes_count_in_region' => new \Twig_Function_Method($this, 'nodesCountInRegion'),
            'cms_get_notifications'     => new \Twig_Function_Method($this, 'getNotifications'),
        ];
    }

    /**
     * Получение текущей папки.
     *
     * @param string|null $field
     *
     * @return null|\SmartCore\Bundle\CMSBundle\Entity\Folder
     */
    public function getCurrentFolder($field = null)
    {
        $folder = $this->container->get('cms.folder')->get($this->container->get('cms.context')->getCurrentFolderId());

        if (!empty($field)) {
            $method = 'get'.ucfirst($field);

            if (method_exists($folder, $method)) {
                return $folder->$method();
            }
        }

        return $folder;
    }

    /**
     * Получение полной ссылки на папку, указав её id. Если не указать ид папки, то вернётся текущий путь.
     *
     * @param mixed|null $data
     *
     * @return string
     */
    public function generateFolderPath($data = null)
    {
        return $this->container->get('cms.folder')->getUri($data);
    }

    /**
     * @param  Region|int $region
     *
     * @return int
     */
    public function nodesCountInRegion($region)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');

        return $em->getRepository('CMSBundle:Node')->countInRegion($region);
    }

    /**
     * @return array
     */
    public function getNotifications()
    {
        $data = [];

        foreach ($this->container->get('cms.module')->all() as $module) {
            $notices = $module->getNotifications();

            if (!empty($notices)) {
                $data['notifications'][$module->getName()] = $notices;
            }
        }

        return $data;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'smart_core_cms_twig_extension';
    }
}
