<?php

namespace SmartCore\Bundle\CMSBundle\Engine;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class EngineContext
{
    protected $base_path;
    protected $current_folder_id = 1;
    protected $current_folder_path;
    protected $current_node_id = null;

    /**
     * Путь к глобальным ресурсам. Может быть на другом домене, например 'http://site.com/assets/'
     *
     * @var string
     */
    protected $global_assets;

    /**
     * Относительный путь к теме оформления.
     *
     * @var string
     */
    protected $theme_path;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container A ContainerInterface instance
     */
    public function __construct(ContainerInterface $container)
    {
        $this->base_path = $container->get('request')->getBasePath() . '/';

        $this->setCurrentFolderPath($this->base_path);
        $this->setGlobalAssets($this->base_path . 'assets/');
        $this->setThemePath($this->base_path . 'theme/');
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->base_path;
    }

    /**
     * @param int $current_folder_id
     * @return $this
     */
    public function setCurrentFolderId($current_folder_id)
    {
        $this->current_folder_id = $current_folder_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getCurrentFolderId()
    {
        return $this->current_folder_id;
    }

    /**
     * @param string $current_folder_path
     * @return $this
     */
    public function setCurrentFolderPath($current_folder_path)
    {
        $this->current_folder_path = $current_folder_path;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrentFolderPath()
    {
        return $this->current_folder_path;
    }

    /**
     * @param int $current_node_id
     * @return $this
     */
    public function setCurrentNodeId($current_node_id)
    {
        $this->current_node_id = $current_node_id;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCurrentNodeId()
    {
        return $this->current_node_id;
    }

    /**
     * @param string $global_assets
     * @return $this
     */
    public function setGlobalAssets($global_assets)
    {
        $this->global_assets = $global_assets;
        return $this;
    }

    /**
     * @return string
     */
    public function getGlobalAssets()
    {
        return $this->global_assets;
    }

    /**
     * @param string $theme_path
     * @return $this
     */
    public function setThemePath($theme_path)
    {
        $this->theme_path = $theme_path;
        return $this;
    }

    /**
     * @return string
     */
    public function getThemePath()
    {
        return $this->theme_path;
    }
}
