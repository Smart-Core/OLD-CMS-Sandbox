<?php

namespace SmartCore\Module\Texter\Service;

use Doctrine\ORM\EntityManager;
use RickySu\Tagcache\Adapter\TagcacheAdapter;

class TexterService
{
    /**
     * @var \RickySu\Tagcache\Adapter\TagcacheAdapter
     */
    protected $cache;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param TagcacheAdapter $cache
     * @param EntityManager $em
     */
    public function __construct(TagcacheAdapter $cache, EntityManager $em)
    {
        $this->cache = $cache;
        $this->em    = $em;
    }

    /**
     * @param $item_id
     * @param null $node_id
     * @return mixed|\SmartCore\Module\Texter\Entity\Item
     */
    public function get($item_id, $node_id = null)
    {
        $cache_key = md5('smart_module.texter' . $item_id);

        if (false == $item = $this->cache->get($cache_key)) {
            $item = $this->em->find('TexterModule:Item', $item_id);

            if ($node_id) {
                $this->cache->set($cache_key, $item, ['smart_module.texter', 'node_' . $node_id]);
            }
        }

        return $item;
    }
}
