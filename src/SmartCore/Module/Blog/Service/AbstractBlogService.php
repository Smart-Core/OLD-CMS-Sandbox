<?php

namespace SmartCore\Module\Blog\Service;

use Doctrine\Common\Cache\Cache;

abstract class AbstractBlogService
{
    /**
     * @var \SmartCore\Module\Blog\Repository\ArticleRepositoryInterface
     */
    protected $articlesRepo;

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var integer
     */
    protected $itemsPerPage;

    /**
     * @return integer
     */
    public function getItemsCountPerPage()
    {
        return $this->itemsPerPage;
    }

    /**
     * @param integer $count
     * @return $this
     */
    public function setItemsCountPerPage($count)
    {
        $this->itemsPerPage = $count;

        return $this;
    }
}
