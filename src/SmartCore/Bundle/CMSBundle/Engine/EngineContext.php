<?php

namespace SmartCore\Bundle\CMSBundle\Engine;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class EngineContext
{
    protected $current_folder_id = 1;
    protected $current_folder_path = '/';
    protected $current_node_id = null;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        if ($requestStack->getMasterRequest() instanceof Request) {
            $this->setCurrentFolderPath($requestStack->getMasterRequest()->getBasePath().'/');
        }
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
}
