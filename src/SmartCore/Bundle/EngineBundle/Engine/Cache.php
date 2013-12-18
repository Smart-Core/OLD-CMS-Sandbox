<?php
namespace SmartCore\Bundle\EngineBundle\Engine;

use SmartCore\Bundle\EngineBundle\Entity\Node;

class Cache
{
    /**
     * @var string
     */
    protected $cache_path;

    public function __construct($kernel)
    {
        $this->cache_path = realpath($kernel->getCacheDir()) . '/smart_core/';

        if (!is_dir($this->cache_path)) {
            mkdir($this->cache_path, 0777, true);
        }
    }

    /**
     * Поместить ноду в кеш.
     *
     * @param Node
     */
    public function setNode(Node $node)
    {
        file_put_contents($this->cache_path . 'node_' . $node->getId(), serialize($node));
    }

    /**
     * Получить ноду из кеша.
     *
     * @param integer $node_id
     * @return Node
     */
    public function getNode($node_id)
    {
        if ($this->hasNode($node_id)) {
            return unserialize(file_get_contents($this->cache_path . 'node_' . $node_id));
        } else {
            return null;
        }
    }

    /**
     * Проверка наличия ноды в кеше.
     *
     * @param integer $node_id
     * @return bool
     */
    public function hasNode($node_id)
    {
        if (file_exists($this->cache_path . 'node_' . $node_id)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Удаление ноды из кеша
     * @param integer $node_id
     */
    public function removeNode($node_id)
    {
        @unlink($this->cache_path . 'node_' . $node_id);
    }
}
