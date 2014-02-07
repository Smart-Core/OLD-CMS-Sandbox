<?php

namespace SmartCore\Module\Blog\EventListener;

use Doctrine\Common\Cache\Cache;
use SmartCore\Module\Blog\SmartBlogEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CacheInvalidateListener implements EventSubscriberInterface
{
    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @param Cache $cache
     */
    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            SmartBlogEvents::ARTICLE_POST_DELETE  => 'articleUpdate',
            SmartBlogEvents::ARTICLE_POST_UPDATE  => 'articleUpdate',
            SmartBlogEvents::CATEGORY_POST_UPDATE => 'invalidateKnpMenuCategoryTree',
            SmartBlogEvents::TAG_POST_DELETE      => 'invalidateTagCloudZend',
            SmartBlogEvents::TAG_POST_UPDATE      => 'invalidateTagCloudZend',
        ];
    }

    public function articleUpdate()
    {
        $this->invalidateArchiveMonthly();
        $this->invalidateTagCloudZend();
    }

    public function invalidateArchiveMonthly()
    {
        $this->cache->delete('archive_monthly');
    }

    public function invalidateKnpMenuCategoryTree()
    {
        $this->cache->delete('knp_menu_category_tree');
    }

    public function invalidateTagCloudZend()
    {
        $this->cache->delete('tag_cloud_zend');
    }
}
