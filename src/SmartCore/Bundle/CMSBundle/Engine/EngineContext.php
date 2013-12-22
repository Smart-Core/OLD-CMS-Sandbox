<?php

namespace SmartCore\Bundle\CMSBundle\Engine;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class EngineContext
{
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
        $base_path = $container->get('request')->getBasePath() . '/';

        $this->setCurrentFolderPath($base_path);
        $this->setGlobalAssets($base_path . 'assets/');
        $this->setThemePath($base_path . 'theme/');
    }

    public function setCurrentFolderId($current_folder_id)
    {
        $this->current_folder_id = $current_folder_id;

        return $this;
    }

    public function getCurrentFolderId()
    {
        return $this->current_folder_id;
    }

    public function setCurrentFolderPath($current_folder_path)
    {
        $this->current_folder_path = $current_folder_path;

        return $this;
    }

    public function getCurrentFolderPath()
    {
        return $this->current_folder_path;
    }

    public function setCurrentNodeId($current_node_id)
    {
        $this->current_node_id = $current_node_id;
    }

    public function getCurrentNodeId()
    {
        return $this->current_node_id;
    }

    public function setGlobalAssets($global_assets)
    {
        $this->global_assets = $global_assets;

        return $this;
    }

    public function getGlobalAssets()
    {
        return $this->global_assets;
    }

    public function setThemePath($theme_path)
    {
        $this->theme_path = $theme_path;

        return $this;
    }

    public function getThemePath()
    {
        return $this->theme_path;
    }
}
