<?php

namespace SmartCore\Module\Blog\Service;

abstract class AbstractBlogService
{
    /**
     * @var \SmartCore\Module\Blog\Repository\ArticleRepositoryInterface
     */
    protected $articlesRepo;

    /**
     * @var \Doctrine\Common\Cache\Cache
     */
    protected $cache;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var int
     */
    protected $itemsPerPage;

    /**
     * @return int
     */
    public function getItemsCountPerPage()
    {
        return $this->itemsPerPage;
    }

    /**
     * @param int $count
     *
     * @return $this
     */
    public function setItemsCountPerPage($count)
    {
        $this->itemsPerPage = $count;

        return $this;
    }
}
