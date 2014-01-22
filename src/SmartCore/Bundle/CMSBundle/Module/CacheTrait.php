<?php

namespace SmartCore\Bundle\CMSBundle\Module;

trait CacheTrait
{
    /**
     * @return \RickySu\Tagcache\Adapter\TagcacheAdapter
     */
    protected function getCacheService()
    {
        return $this->get('tagcache');
    }
}
